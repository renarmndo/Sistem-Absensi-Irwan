<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Unit');

use App\Models\Attendance;
use App\Models\OfficeSetting;
use App\Models\Salary;
use App\Models\User;
use App\Services\PayrollCalculator;

beforeEach(function () {
    OfficeSetting::factory()->create(['effective_work_days' => 20]);
    $this->karyawan = User::factory()->karyawan()->create();
});

test('calculates take home pay correctly with no alfa', function () {
    Salary::factory()->create([
        'user_id' => $this->karyawan->id,
        'basic_salary' => 5_000_000,
        'allowance' => 500_000,
    ]);

    for ($i = 1; $i <= 20; $i++) {
        Attendance::factory()->create([
            'user_id' => $this->karyawan->id,
            'date' => now()->startOfMonth()->addDays($i - 1)->format('Y-m-d'),
            'clock_in' => '07:30:00',
            'clock_out' => '16:30:00',
        ]);
    }

    $calculator = app(PayrollCalculator::class);
    $result = $calculator->calculateForUser($this->karyawan, now()->month, now()->year);

    expect($result['basic_salary'])->toEqual(5000000);
    expect($result['allowance'])->toEqual(500000);
    expect($result['take_home_pay'])->toEqual(5500000.0);
});

test('calculates cut for alfa days', function () {
    Salary::factory()->create([
        'user_id' => $this->karyawan->id,
        'basic_salary' => 5_000_000,
        'allowance' => 500_000,
    ]);

    $calculator = app(PayrollCalculator::class);
    $result = $calculator->calculateForUser($this->karyawan, now()->month, now()->year);

    $dailyRate = 5000000 / 20;
    expect($result['cuts'])->toEqual($dailyRate * $result['total_absence_alfa']);
    expect($result['take_home_pay'])->toEqual(max(0, 5500000.0 - $result['cuts']));
});

test('generates payroll for all karyawan', function () {
    User::factory()->count(3)->karyawan()->create();

    $calculator = app(PayrollCalculator::class);
    $results = $calculator->generatePayroll(now()->month, now()->year);

    expect($results)->toHaveCount(4);
});

test('counts attendance correctly for given month', function () {
    $calculator = app(PayrollCalculator::class);

    for ($i = 1; $i <= 15; $i++) {
        Attendance::factory()->create([
            'user_id' => $this->karyawan->id,
            'date' => now()->startOfMonth()->addDays($i - 1)->format('Y-m-d'),
        ]);
    }

    $count = $calculator->countAttendance($this->karyawan->id, now()->month, now()->year);

    expect($count)->toBe(15);
});

test('month name returns correct indonesian', function () {
    expect(PayrollCalculator::monthName(1))->toBe('Januari');
    expect(PayrollCalculator::monthName(12))->toBe('Desember');
    expect(PayrollCalculator::monthName(13))->toBe('Unknown');
});
