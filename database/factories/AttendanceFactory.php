<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $clockIn = fake()->dateTimeBetween('07:00', '09:00');
        $isLate = $clockIn->format('H:i') > '08:00';
        $clockOut = (clone $clockIn)->modify('+'.rand(8, 10).' hours');
        $date = $clockIn->format('Y-m-d');

        return [
            'user_id' => User::factory(),
            'date' => $date,
            'clock_in' => $clockIn->format('H:i:s'),
            'clock_out' => $clockOut->format('H:i:s'),
            'lat_in' => '-6.2088',
            'long_in' => '106.8456',
            'distance_in' => fake()->randomFloat(2, 1, 50),
            'selfie_in' => 'selfies/test.jpg',
            'lat_out' => '-6.2088',
            'long_out' => '106.8456',
            'distance_out' => fake()->randomFloat(2, 1, 50),
            'selfie_out' => 'selfies/test-out.jpg',
            'status' => $isLate ? 'Terlambat' : 'Hadir',
        ];
    }

    public function terlambat(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in' => fake()->dateTimeBetween('08:01', '10:00')->format('H:i:s'),
            'status' => 'Terlambat',
        ]);
    }

    public function tanpaPulang(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_out' => null,
            'selfie_out' => null,
        ]);
    }
}
