<?php

use App\Models\LeaveRequest;
use App\Models\User;

test('karyawan can view their leave requests', function () {
    $user = User::factory()->karyawan()->create();
    LeaveRequest::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('leave-requests.index'));

    $response->assertOk();
});

test('karyawan can create leave request', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('leave-requests.create'));

    $response->assertOk();
});

test('karyawan can store leave request', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->post(route('leave-requests.store'), [
        'type' => 'Sakit',
        'start_date' => now()->addDay()->format('Y-m-d'),
        'end_date' => now()->addDays(3)->format('Y-m-d'),
        'reason' => 'Demam tinggi',
    ]);

    $response->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $user->id,
        'type' => 'Sakit',
        'status' => 'Pending',
    ]);
});

test('hrd can approve pending leave request', function () {
    $hrd = User::factory()->hrd()->create();
    $leave = LeaveRequest::factory()->create();

    $response = $this->actingAs($hrd)->post(route('leave-requests.approve', $leave));

    $response->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseHas('leave_requests', [
        'id' => $leave->id,
        'status' => 'Approved',
        'approved_by' => $hrd->id,
    ]);
});

test('hrd can reject leave request with reason', function () {
    $hrd = User::factory()->hrd()->create();
    $leave = LeaveRequest::factory()->create();

    $response = $this->actingAs($hrd)->post(route('leave-requests.reject', $leave), [
        'rejection_reason' => 'Dokumen tidak lengkap',
    ]);

    $response->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseHas('leave_requests', [
        'id' => $leave->id,
        'status' => 'Rejected',
        'rejection_reason' => 'Dokumen tidak lengkap',
    ]);
});

test('karyawan cannot approve leave requests', function () {
    $user = User::factory()->karyawan()->create();
    $leave = LeaveRequest::factory()->create();

    $response = $this->actingAs($user)->post(route('leave-requests.approve', $leave));

    $response->assertForbidden();
});

test('karyawan cannot reject leave requests', function () {
    $user = User::factory()->karyawan()->create();
    $leave = LeaveRequest::factory()->create();

    $response = $this->actingAs($user)->post(route('leave-requests.reject', $leave), [
        'rejection_reason' => 'Alasan',
    ]);

    $response->assertForbidden();
});

test('hrd sees all leave requests in index', function () {
    $hrd = User::factory()->hrd()->create();
    LeaveRequest::factory()->count(3)->create();

    $response = $this->actingAs($hrd)->get(route('leave-requests.index'));

    $response->assertOk();
});

test('non-karyawan cannot access create form', function () {
    $hrd = User::factory()->hrd()->create();

    $response = $this->actingAs($hrd)->get(route('leave-requests.create'));

    $response->assertForbidden();
});
