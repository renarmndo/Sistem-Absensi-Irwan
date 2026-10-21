<?php

use App\Models\OfficeSetting;
use App\Models\Payroll;
use App\Models\User;

beforeEach(function () {
    OfficeSetting::factory()->create();
});

test('hrd can view payroll list', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('payrolls.index'));

    $response->assertOk();
});

test('karyawan can view their own payroll', function () {
    $user = User::factory()->karyawan()->create();
    Payroll::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('payrolls.index'));

    $response->assertOk();
});

test('karyawan can view their own payroll detail', function () {
    $user = User::factory()->karyawan()->create();
    $payroll = Payroll::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('payrolls.show', $payroll));

    $response->assertOk();
});

test('karyawan cannot view other payroll detail', function () {
    $user = User::factory()->karyawan()->create();
    $other = User::factory()->karyawan()->create();
    $payroll = Payroll::factory()->create(['user_id' => $other->id]);

    $response = $this->actingAs($user)->get(route('payrolls.show', $payroll));

    $response->assertForbidden();
});

test('hrd can access payroll generate form', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('payrolls.create'));

    $response->assertOk();
});

test('karyawan cannot access payroll generate form', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('payrolls.create'));

    $response->assertForbidden();
});

test('hrd can generate payroll', function () {
    $hrd = User::factory()->hrd()->create();
    User::factory()->karyawan()->create();

    $response = $this->actingAs($hrd)->post(route('payrolls.store'), [
        'month' => now()->month,
        'year' => now()->year,
    ]);

    $response->assertRedirect(route('payrolls.index'));
    $response->assertSessionHas('success');
});

test('karyawan cannot download other payroll PDF', function () {
    $user = User::factory()->karyawan()->create();
    $other = User::factory()->karyawan()->create();
    $payroll = Payroll::factory()->create(['user_id' => $other->id]);

    $response = $this->actingAs($user)->get(route('payrolls.pdf', $payroll));

    $response->assertForbidden();
});
