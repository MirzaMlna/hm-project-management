<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Carbon\Carbon;

class WorkerPresenceSeeder extends Seeder
{
    public function run(): void
    {
        $schedule = WorkerPresenceSchedule::first();
        if (!$schedule) {
            $this->command->warn('Tidak ada jadwal presensi, seeding dilewati.');
            return;
        }

        // contoh: 7 hari terakhir
        $dates = collect(range(0, 6))->map(fn($d) => Carbon::today()->subDays($d)->toDateString());

        $workers = Worker::where('is_active', true)->get();

        foreach ($dates as $date) {
            foreach ($workers as $worker) {
                // random: 70% hadir, 30% bolos
                if (rand(1, 100) <= 70) {
                    $presence = WorkerPresence::firstOrCreate([
                        'worker_id' => $worker->id,
                        'date'      => $date,
                    ]);

                    // Simulasikan jam check-in/out berdasarkan jadwal
                    $firstStart  = Carbon::parse($schedule->first_check_in_start);
                    $firstEnd    = Carbon::parse($schedule->first_check_in_end);
                    $secondStart = Carbon::parse($schedule->second_check_in_start);
                    $secondEnd   = Carbon::parse($schedule->second_check_in_end);
                    $outStart    = Carbon::parse($schedule->check_out_start);
                    $outEnd      = Carbon::parse($schedule->check_out_end);

                    $presence->first_check_in  = $firstStart->copy()->addMinutes(rand(0, 30));
                    $presence->second_check_in = $secondStart->copy()->addMinutes(rand(0, 30));
                    $presence->check_out       = $outStart->copy()->addMinutes(rand(0, 60));

                    // Random flag
                    $presence->is_work_earlier = rand(0, 1);
                    $presence->is_work_longer  = rand(0, 1);
                    $presence->is_overtime     = rand(0, 1);

                    $presence->save();
                }
            }
        }

        $this->command->info("Seeding presensi selesai untuk " . count($workers) . " tukang, range " . $dates->count() . " hari.");
    }
}
