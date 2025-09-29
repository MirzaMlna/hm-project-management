<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Worker;

class WorkerPresenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'worker_id' => Worker::inRandomOrder()->first()->id ?? Worker::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'first_check_in' => $this->faker->dateTimeBetween('07:00:00', '08:00:00'),
            'is_work_earlier' => $this->faker->boolean(10),
            'second_check_in' => $this->faker->dateTimeBetween('13:00:00', '14:00:00'),
            'check_out' => $this->faker->dateTimeBetween('17:00:00', '18:30:00'),
            'is_work_longer' => $this->faker->boolean(20),
            'is_overtime' => $this->faker->boolean(15),
        ];
    }
}
