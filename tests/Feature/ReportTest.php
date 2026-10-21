<?php

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\User;

test('pimpinan can view report dashboard', function () {
    $pimpinan = User::factory()->pimpinan()->create();

    $response = $this->actingAs($pimpinan)->get(route('reports.dashboard'));

    $response->assertOk();
});

test('non-pimpinan cannot view report dashboard', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('reports.dashboard'));

    $response->assertForbidden();
});

test('hrd can view report dashboard', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('reports.dashboard'));

    $response->assertOk();
});

test('hrd can view attendance report', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('reports.index'));

    $response->assertOk();
});

test('karyawan cannot view attendance report', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('reports.index'));

    $response->assertForbidden();
});

test('pimpinan can approve payroll', function () {
    $pimpinan = User::factory()->pimpinan()->create();
    $payroll = Payroll::factory()->create();

    $response = $this->actingAs($pimpinan)->post(route('reports.approve-payroll', $payroll));

    $response->assertRedirect(route('reports.dashboard'));
    $this->assertDatabaseHas('payrolls', [
        'id' => $payroll->id,
        'approval_status' => 'approved',
        'approved_by' => $pimpinan->id,
    ]);
});

test('non-pimpinan cannot approve payroll', function () {
    $hrd = User::factory()->hrd()->create();
    $payroll = Payroll::factory()->create();

    $response = $this->actingAs($hrd)->post(route('reports.approve-payroll', $payroll));

    $response->assertForbidden();
});

test('pimpinan can reject payroll', function () {
    $pimpinan = User::factory()->pimpinan()->create();
    $payroll = Payroll::factory()->create();

    $response = $this->actingAs($pimpinan)->post(route('reports.reject-payroll', $payroll));

    $response->assertRedirect(route('reports.dashboard'));
    $this->assertDatabaseHas('payrolls', [
        'id' => $payroll->id,
        'approval_status' => 'rejected',
    ]);
});

test('attendance report filters by employee', function () {
    $hrd = User::factory()->hrd()->create();
    $karyawan = User::factory()->karyawan()->create();

    for ($i = 0; $i < 3; $i++) {
        Attendance::factory()->create([
            'user_id' => $karyawan->id,
            'date' => now()->subDays($i)->format('Y-m-d'),
        ]);
    }

    $response = $this->actingAs($hrd)->get(route('reports.index', ['user_id' => $karyawan->id]));

    $response->assertOk();
});
