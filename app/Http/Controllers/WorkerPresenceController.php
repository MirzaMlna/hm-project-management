<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        return view('worker-presences.index', compact('worker_presence_schedules', 'presences'));
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
}
