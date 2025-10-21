<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkerPresenceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('worker_presence_schedules')->insert([
            'first_check_in_start' => '08:00:00',
            'first_check_in_end'   => '12:00:00',
            'second_check_in_start' => '12:00:00',
            'second_check_in_end'  => '18:00:00',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }
}
