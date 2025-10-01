<?php

namespace App\Http\Controllers;

use App\Exports\WorkerPresenceExport;
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $worker_presence_schedules = WorkerPresenceSchedule::first();

        // Tanggal yang ingin dilihat (default hari ini)
        $date = $request->get('date', Carbon::today()->toDateString());

        // Ambil presensi untuk tanggal tersebut beserta relasi worker -> category
        $presences = WorkerPresence::with(['worker.category'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->paginate(10);

        $categories = WorkerCategory::orderBy('category')->get();

        return view('worker-presences.index', compact('worker_presence_schedules', 'presences', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workerPresence = WorkerPresence::findOrFail($id);
        $workerPresence->delete();
        return redirect()->route('worker-presences.index')
            ->with('success', 'Presensi Tukang Berhasil Dihapus.');
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
        $outStart    = Carbon::parse($schedule->check_out_start);
        $outEnd      = Carbon::parse($schedule->check_out_end);

        // FIRST CHECK IN
        if (is_null($presence->first_check_in)) {
            // Jika lebih awal (2 jam sebelum sampai tepat saat jam mulai)
            if ($now->between($firstStart->copy()->subHours(2), $firstStart)) {
                $presence->first_check_in  = $now;
                $presence->is_work_earlier = true;
                $presence->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Presensi ke-1 (Lebih Awal)',
                    'worker'  => [
                        'name'     => $worker->name,
                        'code'     => $worker->code,
                        'category' => $worker->category->category ?? '-'
                    ]
                ]);
            }
            if ($now->between($firstStart, $firstEnd)) {
                $presence->first_check_in  = $now;
                $presence->is_work_earlier = false;
                $presence->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Presensi ke-1 (Tepat Waktu)',
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
                    'status' => 'success',
                    'message' => 'Presensi ke-2',
                    'worker' => [
                        'name' => $worker->name,
                        'code' => $worker->code,
                        'category' => $worker->category->category ?? '-'
                    ]
                ]);
            }
        }

        // CHECK OUT
        if (is_null($presence->check_out)) {
            if ($now->lt($outStart)) {
                return response()->json(['status' => 'error', 'message' => 'Belum waktunya']);
            }

            $presence->check_out = $now;

            if ($now->between($outStart, $outEnd)) {
                $presence->is_work_longer = false;
                $presence->is_overtime = false;
            } elseif ($now->between($outEnd->copy()->addMinute(), $outEnd->copy()->addHours(2))) {
                $presence->is_work_longer = true;
                $presence->is_overtime = false;
            } elseif ($now->gte($outEnd->copy()->addHours(2))) {
                $presence->is_work_longer = false;
                $presence->is_overtime = true;
            }

            $presence->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi Pulang',
                'worker' => [
                    'name' => $worker->name,
                    'code' => $worker->code,
                    'category' => $worker->category->category ?? '-',
                    'daily_salary' => $worker->daily_salary->daily_salary ?? '-'
                ]
            ]);
        }

        return response()->json(['status' => 'info', 'message' => 'Semua presensi untuk hari ini sudah tercatat.']);
    }

    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from'   => 'required|date',
            'date_to'     => 'required|date|after_or_equal:date_from',
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
            $period[] = $d->toDateString(); // yyyy-mm-dd
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
