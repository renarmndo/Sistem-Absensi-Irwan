<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'basic_salary' => fake()->numberBetween(3_000_000, 15_000_000),
            'allowance' => fake()->numberBetween(200_000, 2_000_000),
            'effective_at' => now()->startOfYear(),
        ];
    }
}
