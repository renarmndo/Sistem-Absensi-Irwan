<?php

use App\Models\Role;
use App\Models\Salary;
use App\Models\User;

beforeEach(function () {
    Role::factory()->karyawan()->create();
    Role::factory()->hrd()->create();
    Role::factory()->pimpinan()->create();
});

test('hrd can view salary list', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('salaries.index'));

    $response->assertOk();
});

test('hrd can create a salary', function () {
    $hrd = User::factory()->hrd()->create();
    $karyawan = User::factory()->karyawan()->create();

    $response = $this->actingAs($hrd)->post(route('salaries.store'), [
        'user_id' => $karyawan->id,
        'basic_salary' => 5_000_000,
        'allowance' => 500_000,
        'effective_at' => now()->startOfMonth()->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('salaries.index'));
    $this->assertDatabaseHas('salaries', [
        'user_id' => $karyawan->id,
        'basic_salary' => 5_000_000,
        'allowance' => 500_000,
    ]);
});

test('karyawan cannot access salary management', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('salaries.index'));

    $response->assertForbidden();
});

test('karyawan cannot create salary', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('salaries.create'));

    $response->assertForbidden();
});

test('hrd can edit salary', function () {
    $hrd = User::factory()->hrd()->create();
    $salary = Salary::factory()->create();

    $response = $this->actingAs($hrd)->put(route('salaries.update', $salary), [
        'user_id' => $salary->user_id,
        'basic_salary' => 6_000_000,
        'allowance' => 750_000,
        'effective_at' => $salary->effective_at->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('salaries.index'));
    $this->assertDatabaseHas('salaries', [
        'id' => $salary->id,
        'basic_salary' => 6_000_000,
    ]);
});

test('hrd can delete salary', function () {
    $hrd = User::factory()->hrd()->create();
    $salary = Salary::factory()->create();

    $response = $this->actingAs($hrd)->delete(route('salaries.destroy', $salary));

    $response->assertRedirect(route('salaries.index'));
    $this->assertDatabaseMissing('salaries', ['id' => $salary->id]);
});

test('pimpinan cannot access salary management', function () {
    $pimpinan = User::factory()->pimpinan()->create();

    $response = $this->actingAs($pimpinan)->get(route('salaries.index'));

    $response->assertForbidden();
});
