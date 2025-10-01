<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerCategory;
use App\Models\WorkerPresence;
use App\Models\WorkerPresenceSchedule;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        // $this->call(WorkerPresenceScheduleSeeder::class);

        //? Factory
        $categories = ['Tukang Jawa', 'Tukang Banjar'];
        foreach ($categories as $category) {
            WorkerCategory::factory()->create(['category' => $category]);
        }
        Worker::factory(10)->create();
        WorkerPresenceSchedule::factory()->create();
        WorkerPresence::factory(30)->create();
    }
}
