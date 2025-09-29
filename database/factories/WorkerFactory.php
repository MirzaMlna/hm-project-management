<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WorkerCategory;

class WorkerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'worker_category_id' => WorkerCategory::inRandomOrder()->first()->id ?? WorkerCategory::factory(),
            'code' => strtoupper($this->faker->bothify('W###')),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'photo' => null,
            'daily_salary' => $this->faker->numberBetween(100000, 200000),
            'is_active' => $this->faker->boolean(90),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
