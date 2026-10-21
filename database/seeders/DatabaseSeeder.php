<?php

namespace Database\Seeders;

use App\Models\OfficeSetting;
use App\Models\Role;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $karyawan = Role::firstOrCreate(['name' => 'karyawan', 'display_name' => 'Karyawan']);
        $hrd = Role::firstOrCreate(['name' => 'hrd', 'display_name' => 'HRD']);
        $pimpinan = Role::firstOrCreate(['name' => 'pimpinan', 'display_name' => 'Pimpinan']);

        $userKaryawan = User::firstOrCreate(
            ['email' => 'karyawan@example.com'],
            [
                'role_id' => $karyawan->id,
                'employee_id' => 'KRY-001',
                'name' => 'Karyawan User',
                'phone' => '081234567892',
                'position' => 'Staff',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'hrd@example.com'],
            [
                'role_id' => $hrd->id,
                'employee_id' => 'ADM-001',
                'name' => 'Admin HRD',
                'phone' => '081234567890',
                'position' => 'HRD Manager',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'direktur@example.com'],
            [
                'role_id' => $pimpinan->id,
                'employee_id' => 'DIR-001',
                'name' => 'Direktur',
                'phone' => '081234567891',
                'position' => 'Direktur Utama',
                'password' => bcrypt('password'),
            ]
        );

        if (! Salary::where('user_id', $userKaryawan->id)->exists()) {
            Salary::create([
                'user_id' => $userKaryawan->id,
                'basic_salary' => 4_500_000,
                'allowance' => 500_000,
                'effective_at' => now()->startOfYear(),
            ]);
        }

        if (! OfficeSetting::exists()) {
            OfficeSetting::create([
                'office_name' => 'Kantor Pusat PT. Pesona Putra Perkasa',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'radius_meter' => 50,
                'work_start_time' => '08:00:00',
                'work_end_time' => '17:00:00',
                'effective_work_days' => 20,
            ]);
        }
    }
}
