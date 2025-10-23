<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\WorkerCategory;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorkerPresencePerCategoryExport; // kalau kamu sudah punya export-nya
use Illuminate\Support\Facades\Validator;

class WorkerPresenceClickController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $schedule = WorkerPresenceSchedule::first();

        $workers = Worker::with('category')
            ->where('is_active', true)
            ->orderBy('worker_category_id')
            ->orderBy('name')
            ->get();

        // Ambil semua presensi hari ini
        $presences = WorkerPresence::whereDate('date', $date)->get()->keyBy('worker_id');

        return view('worker-presence-click.index', compact('workers', 'presences', 'schedule', 'date'));
    }

    public function saveAll(Request $request)
    {
        $data = $request->input('presences', []);
        $date = Carbon::today()->toDateString();

        foreach ($data as $workerId => $presenceData) {
            $presence = WorkerPresence::firstOrNew([
                'worker_id' => $workerId,
                'date' => $date,
            ]);

            // Checkbox bisa dikembalikan ke false
            $presence->first_check_in = !empty($presenceData['first_check_in']) ? Carbon::now() : null;
            $presence->second_check_in = !empty($presenceData['second_check_in']) ? Carbon::now() : null;
            $presence->work_longer_count = $presenceData['work_longer_count'] ?? 0;
            $presence->is_overtime = !empty($presenceData['is_overtime']);

            $presence->save();
        }

        return back()->with('success', 'Semua perubahan presensi berhasil disimpan.');
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
