<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'holiday_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'description' => fake()->sentence(3),
        ];
    }
}
