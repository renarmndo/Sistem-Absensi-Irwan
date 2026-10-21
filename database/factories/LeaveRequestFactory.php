<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['Sakit', 'Izin', 'Cuti']),
            'start_date' => now()->addDays(fake()->numberBetween(1, 5)),
            'end_date' => now()->addDays(fake()->numberBetween(6, 10)),
            'reason' => fake()->sentence(),
            'status' => 'Pending',
            'approved_by' => null,
            'reviewed_at' => null,
            'rejection_reason' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Approved',
            'approved_by' => User::factory()->hrd(),
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Rejected',
            'approved_by' => User::factory()->hrd(),
            'reviewed_at' => now(),
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
