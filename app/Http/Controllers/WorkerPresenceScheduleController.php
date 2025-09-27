<?php

namespace App\Http\Controllers;

use App\Models\WorkerPresenceSchedule;
use Illuminate\Http\Request;

class WorkerPresenceScheduleController extends Controller
{
    public function index()
    {
        $schedule = WorkerPresenceSchedule::first();
        return view('worker-presence-schedules.index', compact('schedule'));
    }

    public function storeOrUpdate(Request $request)
    {
        $this->validateTimes($request);

        $schedule = WorkerPresenceSchedule::first();

        if ($schedule) {
            $schedule->update($request->only([
                'first_check_in_start',
                'first_check_in_end',
                'second_check_in_start',
                'second_check_in_end',
                'check_out_start',
                'check_out_end'
            ]));
            $message = 'Jadwal absensi berhasil diperbarui.';
        } else {
            WorkerPresenceSchedule::create($request->only([
                'first_check_in_start',
                'first_check_in_end',
                'second_check_in_start',
                'second_check_in_end',
                'check_out_start',
                'check_out_end'
            ]));
            $message = 'Jadwal absensi berhasil disimpan.';
        }

        return redirect()->route('worker-presence-schedules.index')->with('success', $message);
    }

    private function validateTimes(Request $request)
    {
        $rules = [
            'first_check_in_start'  => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'first_check_in_end'    => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after:first_check_in_start'],
            'second_check_in_start' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after_or_equal:first_check_in_end'],
            'second_check_in_end'   => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after:second_check_in_start'],
            'check_out_start'       => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after_or_equal:second_check_in_end'],
            'check_out_end'         => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after:check_out_start'],
        ];

        $messages = [
            'after' => ':attribute harus lebih besar dari waktu sebelumnya.',
            'after_or_equal' => ':attribute harus sama atau lebih besar dari waktu sebelumnya.',
            'regex' => ':attribute harus dalam format jam (HH:mm atau HH:mm:ss).',
        ];

        $request->validate($rules, $messages);
    }
}
