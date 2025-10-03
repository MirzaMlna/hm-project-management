<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerCategory;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(WorkerPresenceScheduleSeeder::class);

        // 1. Buat kategori
        // $categories = ['Tukang Jawa', 'Tukang Banjar'];
        // foreach ($categories as $category) {
        //     WorkerCategory::factory()->create(['category' => $category]);
        // }

        // // 2. Buat 10 worker
        // $workers = Worker::factory(10)->create();

        // // 3. Buat schedule (bisa satu baris saja)
        // WorkerPresenceSchedule::factory()->create();

        // // 4. Buat presensi per worker setiap hari
        // $startDate = Carbon::now()->subDays(29); // 30 hari terakhir
        // $endDate = Carbon::now();

        // foreach ($workers as $worker) {
        //     $date = $startDate->copy();
        //     while ($date->lte($endDate)) {
        //         WorkerPresence::factory()->for($worker)->state([
        //             'date' => $date->format('Y-m-d')
        //         ])->create();
        //         $date->addDay();
        //     }
        // }
    }
}
