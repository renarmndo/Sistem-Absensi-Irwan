<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'office_name' => 'Kantor '.fake()->company(),
            'latitude' => '-6.2088',
            'longitude' => '106.8456',
            'radius_meter' => 50,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'effective_work_days' => 20,
        ];
    }
}
