<?php

use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    Role::factory()->karyawan()->create();
    Role::factory()->hrd()->create();
    Role::factory()->pimpinan()->create();
});

test('guest is redirected to login', function () {
    $response = $this->get('/');
    $response->assertRedirect(route('login'));
});

test('guest cannot access attendance', function () {
    $response = $this->get(route('attendance.index'));
    $response->assertRedirect(route('login'));
});

test('guest cannot access leave requests', function () {
    $response = $this->get(route('leave-requests.index'));
    $response->assertRedirect(route('login'));
});

test('guest cannot access payroll', function () {
    $response = $this->get(route('payrolls.index'));
    $response->assertRedirect(route('login'));
});

test('guest cannot access salaries', function () {
    $response = $this->get(route('salaries.index'));
    $response->assertRedirect(route('login'));
});

test('guest cannot access reports', function () {
    $response = $this->get(route('reports.index'));
    $response->assertRedirect(route('login'));
});

test('karyawan cannot access hrd salary routes', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('salaries.create'));
    $response->assertForbidden();
});

test('pimpinan cannot access hrd salary routes', function () {
    $pimpinan = User::factory()->pimpinan()->create();

    $response = $this->actingAs($pimpinan)->get(route('salaries.create'));
    $response->assertForbidden();
});

test('hrd gate works for hrd users', function () {
    $hrd = User::factory()->hrd()->create();

    $this->assertTrue($hrd->isHrd());
    $this->assertFalse($hrd->isKaryawan());
    $this->assertFalse($hrd->isPimpinan());
});

test('karyawan gate works for karyawan users', function () {
    $user = User::factory()->karyawan()->create();

    $this->assertTrue($user->isKaryawan());
    $this->assertFalse($user->isHrd());
    $this->assertFalse($user->isPimpinan());
});

test('pimpinan gate works for pimpinan users', function () {
    $pimpinan = User::factory()->pimpinan()->create();

    $this->assertTrue($pimpinan->isPimpinan());
    $this->assertFalse($pimpinan->isKaryawan());
    $this->assertFalse($pimpinan->isHrd());
});

test('arch controllers have proper suffix', function () {
    expect('App\Http\Controllers')
        ->toHaveSuffix('Controller');
});
