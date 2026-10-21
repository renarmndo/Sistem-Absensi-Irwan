<?php

use App\Models\Attendance;
use App\Models\OfficeSetting;
use App\Models\User;

test('attendance index page loads for karyawan', function () {
    $user = User::factory()->karyawan()->create();
    OfficeSetting::factory()->create();

    $response = $this->actingAs($user)->get(route('attendance.index'));

    $response->assertOk();
});

test('attendance history page loads for karyawan', function () {
    $user = User::factory()->karyawan()->create();

    $response = $this->actingAs($user)->get(route('attendance.history'));

    $response->assertOk();
});

test('karyawan can clock in with valid JSON', function () {
    $user = User::factory()->karyawan()->create();
    OfficeSetting::factory()->create([
        'work_start_time' => '23:59:00',
    ]);

    $selfieBase64 = 'data:image/png;base64,'.base64_encode('fake-image-content');

    $response = $this->actingAs($user)->postJson(route('attendance.store'), [
        'type' => 'in',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'selfie' => $selfieBase64,
    ]);

    $response->assertOk();
    expect($response['success'])->toBeTrue();

    $this->assertDatabaseHas('attendances', [
        'user_id' => $user->id,
        'status' => 'Hadir',
    ]);
});

test('prevents duplicate clock in', function () {
    $user = User::factory()->karyawan()->create();
    OfficeSetting::factory()->create();

    Attendance::factory()->create([
        'user_id' => $user->id,
        'date' => now()->format('Y-m-d'),
    ]);

    $selfieBase64 = 'data:image/png;base64,'.base64_encode('fake-image-content');

    $response = $this->actingAs($user)->postJson(route('attendance.store'), [
        'type' => 'in',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'selfie' => $selfieBase64,
    ]);

    expect($response['success'])->toBeFalse();
});

test('rejects invalid selfie format', function () {
    $user = User::factory()->karyawan()->create();
    OfficeSetting::factory()->create();

    $response = $this->actingAs($user)->post(route('attendance.store'), [
        'type' => 'in',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'selfie' => 'not-a-valid-base64-image',
    ]);

    $response->assertSessionHasErrors('selfie');
});

test('rejects attendance outside geofence radius', function () {
    $user = User::factory()->karyawan()->create();
    OfficeSetting::factory()->create([
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'radius_meter' => 50,
    ]);

    $selfieBase64 = 'data:image/png;base64,'.base64_encode('fake-image-content');

    $response = $this->actingAs($user)->postJson(route('attendance.store'), [
        'type' => 'in',
        'latitude' => -6.3000,
        'longitude' => 106.9000,
        'selfie' => $selfieBase64,
    ]);

    expect($response['success'])->toBeFalse();
});

test('non-karyawan cannot submit attendance', function () {
    $user = User::factory()->hrd()->create();
    OfficeSetting::factory()->create();

    $selfieBase64 = 'data:image/png;base64,'.base64_encode('fake-image-content');

    $response = $this->actingAs($user)->postJson(route('attendance.store'), [
        'type' => 'in',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'selfie' => $selfieBase64,
    ]);

    $response->assertForbidden();
});
