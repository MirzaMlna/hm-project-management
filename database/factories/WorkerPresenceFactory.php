<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Worker;

class WorkerPresenceFactory extends Factory
{
    public function definition(): array
    {
        // Tentukan jumlah check per hari: 1, 2, atau 3 kali
        $checkCount = $this->faker->numberBetween(1, 3);

        // Default semua null
        $firstCheckIn = $secondCheckIn = $checkOut = null;

        if ($checkCount >= 1) {
            $firstCheckIn = $this->faker->dateTimeBetween('07:00:00', '08:00:00');
        }
        if ($checkCount >= 2) {
            $secondCheckIn = $this->faker->dateTimeBetween('13:00:00', '14:00:00');
        }
        if ($checkCount === 3) {
            $checkOut = $this->faker->dateTimeBetween('17:00:00', '18:30:00');
        }

        return [
            'worker_id' => Worker::inRandomOrder()->first()->id ?? Worker::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'first_check_in' => $firstCheckIn,
            'is_work_earlier' => $this->faker->boolean(10),
            'second_check_in' => $secondCheckIn,
            'check_out' => $checkOut,
            'is_work_longer' => $this->faker->boolean(20),
            'is_overtime' => $this->faker->boolean(15),
        ];
    }
}
