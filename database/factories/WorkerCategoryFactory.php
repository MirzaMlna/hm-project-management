<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => $this->faker->unique()->jobTitle(), // bisa diganti custom list
        ];
    }
}
