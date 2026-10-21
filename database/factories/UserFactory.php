<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    private static function ensureRole(string $name, string $displayName): int
    {
        return Role::firstOrCreate(
            ['name' => $name],
            ['display_name' => $displayName]
        )->id;
    }

    public function definition(): array
    {
        return [
            'role_id' => self::ensureRole('karyawan', 'Karyawan'),
            'employee_id' => fake()->unique()->numerify('EMP-#####'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->jobTitle(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function karyawan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => self::ensureRole('karyawan', 'Karyawan'),
        ]);
    }

    public function hrd(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => self::ensureRole('hrd', 'HRD'),
        ]);
    }

    public function pimpinan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => self::ensureRole('pimpinan', 'Pimpinan'),
        ]);
    }
}
