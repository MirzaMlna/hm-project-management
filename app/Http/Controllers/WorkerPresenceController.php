<?php

namespace App\Http\Controllers;

use App\Exports\WorkerPresencePerCategoryExport;
use App\Models\Worker;
use App\Models\WorkerCategory;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WorkerPresenceController extends Controller
{
    public function index(Request $request)
    {
        $worker_presence_schedules = WorkerPresenceSchedule::first();

        // Tanggal default = hari ini
        $date = $request->get('date', Carbon::today()->toDateString());

        $presences = WorkerPresence::with(['worker.category'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->paginate(10);

        $categories = WorkerCategory::orderBy('category')->get();

        $totalWorkers = Worker::where('is_active', true)->count();

        $presentWorkers = WorkerPresence::whereDate('date', $date)
            ->whereHas('worker', fn($q) => $q->where('is_active', true))
            ->distinct('worker_id')
            ->count('worker_id');

        $notPresentCount = $totalWorkers - $presentWorkers;

        return view('worker-presences.index', compact(
            'worker_presence_schedules',
            'presences',
            'categories',
            'notPresentCount'
        ));
    }

    public function destroy(string $id)
    {
        $workerPresence = WorkerPresence::findOrFail($id);
        $workerPresence->delete();
        return redirect()->route('worker-presences.index')
            ->with('success', 'Presensi Tukang Berhasil Dihapus.');
    }

    public function preview($hashId)
    {
        $decoded = generateQr($hashId, 'D');
        $workerId = is_array($decoded) && isset($decoded[0]) ? $decoded[0] : null;

        if (!$workerId) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak valid.']);
        }

        $worker = Worker::with('category')->find($workerId);
        if (!$worker || !$worker->is_active) {
            return response()->json(['status' => 'error', 'message' => 'Tukang tidak ditemukan / nonaktif.']);
        }

        return response()->json([
            'status' => 'success',
            'worker' => [
                'photo' => $worker->photo ? asset('storage/' . $worker->photo) : null,
                'name'     => $worker->name,
                'code'     => $worker->code,
                'category' => $worker->category->category ?? '-'
            ]
        ]);
    }

    public function verify($hashId)
    {
        $decoded = generateQr($hashId, 'D');
        $workerId = is_array($decoded) && isset($decoded[0]) ? $decoded[0] : null;

        if (!$workerId) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak valid.']);
        }

        $worker = Worker::find($workerId);
        if (!$worker) {
            return response()->json(['status' => 'error', 'message' => 'Tukang tidak ditemukan.']);
        }

        $schedule = WorkerPresenceSchedule::first();
        if (!$schedule) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal absensi belum diatur.']);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $presence = WorkerPresence::firstOrCreate(
            ['worker_id' => $worker->id, 'date' => $today],
            []
        );

        $firstStart  = Carbon::parse($schedule->first_check_in_start);
        $firstEnd    = Carbon::parse($schedule->first_check_in_end);
        $secondStart = Carbon::parse($schedule->second_check_in_start);
        $secondEnd   = Carbon::parse($schedule->second_check_in_end);

        // FIRST CHECK IN
        if (is_null($presence->first_check_in)) {
            if ($now->between($firstStart->copy()->subHours(2), $firstEnd)) {
                $presence->first_check_in = $now;
                $presence->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Presensi ke-1 Berhasil',
                    'worker'  => [
                        'name'     => $worker->name,
                        'code'     => $worker->code,
                        'category' => $worker->category->category ?? '-'
                    ]
                ]);
            }
        }

        // SECOND CHECK IN
        if (is_null($presence->second_check_in)) {
            if ($now->between($secondStart, $secondEnd)) {
                $presence->second_check_in = $now;
                $presence->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Presensi ke-2 Berhasil',
                    'worker'  => [
                        'photo'    => $worker->photo,
                        'name'     => $worker->name,
                        'code'     => $worker->code,
                        'category' => $worker->category->category ?? '-'
                    ]
                ]);
            }
        }

        // Jika ingin menambahkan kerja lebih lama (manual / otomatis)
        if ($presence->second_check_in && !$now->between($secondStart, $secondEnd)) {
            // contoh logika: kerja lebih lama dihitung per jam
            $presence->work_longer_count += 1; // atau sesuai hitungan kamu
            $presence->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Durasi kerja tambahan dicatat (+1)',
                'worker'  => [
                    'name'     => $worker->name,
                    'code'     => $worker->code,
                    'category' => $worker->category->category ?? '-'
                ]
            ]);
        }

        return response()->json(['status' => 'info', 'message' => 'Semua presensi untuk hari ini sudah tercatat.']);
    }

    public function update(Request $request, string $id)
    {
        $presence = WorkerPresence::findOrFail($id);

        $presence->update([
            'work_longer_count' => $request->input('work_longer_count', 0),
            'is_overtime' => $request->has('is_overtime'),
        ]);

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui.');
    }


    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $start = Carbon::parse($request->date_from)->startOfDay();
        $end   = Carbon::parse($request->date_to)->startOfDay();

        $days = $start->diffInDays($end) + 1;
        if ($days > 30) {
            return redirect()->back()->with('error', 'Range maksimal 30 hari.');
        }

        $period = [];
        $d = $start->copy();
        while ($d->lte($end)) {
            $period[] = $d->toDateString();
            $d->addDay();
        }

        $categories = WorkerCategory::with('workers')->get();

        $presences = WorkerPresence::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy('worker_id');

        return Excel::download(
            new WorkerPresencePerCategoryExport(
                $period,
                $start->format('d F Y') . ' s/d ' . $end->format('d F Y'),
                $categories,
                $presences
            ),
            'presensi_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xlsx'
        );
    }
}
