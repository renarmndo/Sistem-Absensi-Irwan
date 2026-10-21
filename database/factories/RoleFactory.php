<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    private static array $roles = [
        ['name' => 'karyawan', 'display_name' => 'Karyawan'],
        ['name' => 'hrd', 'display_name' => 'HRD'],
        ['name' => 'pimpinan', 'display_name' => 'Pimpinan'],
    ];

    public function definition(): array
    {
        $role = fake()->randomElement(self::$roles);

        return $role;
    }

    public function karyawan(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'karyawan',
            'display_name' => 'Karyawan',
        ]);
    }

    public function hrd(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'hrd',
            'display_name' => 'HRD',
        ]);
    }

    public function pimpinan(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'pimpinan',
            'display_name' => 'Pimpinan',
        ]);
    }
}
