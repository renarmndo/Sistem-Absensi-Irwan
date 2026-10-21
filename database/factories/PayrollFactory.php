<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    public function definition(): array
    {
        $basicSalary = fake()->numberBetween(3_000_000, 10_000_000);
        $allowance = fake()->numberBetween(200_000, 1_000_000);
        $cuts = fake()->numberBetween(0, 500_000);

        return [
            'user_id' => User::factory(),
            'period_month' => now()->month,
            'period_year' => now()->year,
            'total_attendance' => fake()->numberBetween(15, 22),
            'total_absence_alfa' => fake()->numberBetween(0, 5),
            'basic_salary' => $basicSalary,
            'allowance' => $allowance,
            'cuts' => $cuts,
            'take_home_pay' => $basicSalary + $allowance - $cuts,
            'approval_status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'approved',
            'approved_by' => User::factory()->pimpinan(),
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'rejected',
            'approved_by' => User::factory()->pimpinan(),
            'approved_at' => now(),
        ]);
    }
}
