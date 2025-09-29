<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerPresenceScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_check_in_start'  => '07:00:00',
            'first_check_in_end'    => '08:00:00',
            'second_check_in_start' => '13:00:00',
            'second_check_in_end'   => '14:00:00',
            'check_out_start'       => '17:00:00',
            'check_out_end'         => '18:00:00',
        ];
    }
}
