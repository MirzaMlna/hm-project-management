<?php

namespace App\Http\Controllers;

use App\Exports\WorkerPresenceExport;
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
            ->get();

        // Ambil daftar kategori untuk modal filter
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

            if ($now->between($firstStart->copy()->subHours(2), $firstEnd)) {
                $presence->first_check_in  = $now;
                $presence->is_work_earlier = $now->lt($firstStart);
                $presence->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Presensi ke-1',
                    'worker' => [
                        'name' => $worker->name,
                        'code' => $worker->code,
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
                    'category' => $worker->category->category ?? '-'
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
            'category_id' => 'nullable|exists:categories,id',
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

        // buat array tanggal dalam rentang
        $period = [];
        $datesHeader = [];
        $d = $start->copy();
        while ($d->lte($end)) {
            $period[] = $d->toDateString(); // yyyy-mm-dd (cocok untuk pencarian di DB)
            $datesHeader[] = $d->format('d-M'); // header kolom excel
            $d->addDay();
        }

        // ambil worker yang sesuai filter kategori (atau semua)
        $workers = Worker::with('category')
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->orderBy('name')
            ->get();

        // ambil semua presensi dalam rentang dan group by worker_id
        $presences = WorkerPresence::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy('worker_id');

        $rows = [];
        $no = 1;

        foreach ($workers as $worker) {
            $row = [];
            $row[] = $no++;
            $row[] = $worker->name;
            $row[] = $worker->code;
            $row[] = $worker->category->category ?? '-';

            $workerPresences = $presences->get($worker->id, collect());

            $totalPoints = 0;
            $dla = $kll = $lm = 0;

            foreach ($period as $date) {
                $p = $workerPresences->firstWhere('date', $date);

                if ($p) {
                    // hitung berapa kali presensi (count dari 3 field)
                    $count = 0;
                    if ($p->first_check_in) $count++;
                    if ($p->second_check_in) $count++;
                    if ($p->check_out) $count++;

                    // mapping poin:
                    // 0 => 0  (absen)
                    // 1 => 0
                    // 2 => 0.5
                    // >=3 => 1
                    if ($count >= 3) $points = 1;
                    elseif ($count == 2) $points = 0.5;
                    else $points = 0;

                    // akumulasi DLA/KLL/LM berdasar boolean di record
                    if ($p->is_work_earlier) $dla++;
                    if ($p->is_work_longer) $kll++;
                    if ($p->is_overtime) $lm++;
                } else {
                    $points = 0;
                }

                // tambahkan kolom poin hari itu
                // gunakan numeric value (float) supaya Excel mengenali angka
                $row[] = (float) $points;
                $totalPoints += (float) $points;
            }

            // kolom summary
            $row[] = (float) $totalPoints; // total poin periode
            $row[] = (int) $dla;
            $row[] = (int) $kll;
            $row[] = (int) $lm;

            $rows[] = $row;
        }

        $headings = array_merge(
            ['No', 'Nama', 'Kode', 'Kategori'],
            $datesHeader,
            ['Total Kehadiran', 'DLA', 'KLL', 'LM']
        );

        return Excel::download(new WorkerPresenceExport($headings, $rows), 'presensi_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xlsx');
    }
}
