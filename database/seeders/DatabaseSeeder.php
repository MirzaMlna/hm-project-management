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

        // buat kategori fix
        $categories = ['Tukang Jawa', 'Tukang Banjar'];
        foreach ($categories as $category) {
            WorkerCategory::factory()->create(['category' => $category]);
        }

        // pekerja random 10 orang
        Worker::factory(10)->create();

        // jadwal default (1 data aja)
        WorkerPresenceSchedule::factory()->create();

        // dummy presensi 30 data
        WorkerPresence::factory(30)->create();
    }
}
