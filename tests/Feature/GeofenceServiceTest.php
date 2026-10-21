<?php

use App\Models\OfficeSetting;
use App\Services\GeofenceService;

beforeEach(function () {
    OfficeSetting::factory()->create([
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'radius_meter' => 100,
    ]);
});

test('calculates haversine distance correctly', function () {
    $service = app(GeofenceService::class);

    $distance = $service->calculateDistance(-6.2088, 106.8456, -6.2088, 106.8456);
    expect($distance)->toBe(0);

    $distance = $service->calculateDistance(-6.2088, 106.8456, -7.2088, 106.8456);
    expect($distance)->toBeGreaterThan(100000);
    expect($distance)->toBeLessThan(120000);
});

test('returns true when within radius', function () {
    $service = app(GeofenceService::class);

    $result = $service->isWithinRadius(-6.2088, 106.8456);
    expect($result)->toBeTrue();
});

test('returns false when outside radius', function () {
    $service = app(GeofenceService::class);

    $result = $service->isWithinRadius(-7.2088, 106.8456);
    expect($result)->toBeFalse();
});

test('returns 0 distance when no office setting', function () {
    OfficeSetting::truncate();
    $service = app(GeofenceService::class);

    $distance = $service->getDistance(-6.2088, 106.8456);
    expect($distance)->toBe(0);
});

test('returns false when no office setting for radius check', function () {
    OfficeSetting::truncate();
    $service = app(GeofenceService::class);

    $result = $service->isWithinRadius(-6.2088, 106.8456);
    expect($result)->toBeFalse();
});

test('status method returns Hadir when on time', function () {
    $status = GeofenceService::status('08:00:00', '08:00:00');
    expect($status)->toBe('Hadir');

    $status = GeofenceService::status('07:30:00', '08:00:00');
    expect($status)->toBe('Hadir');
});

test('status method returns Terlambat when late', function () {
    $status = GeofenceService::status('08:01:00', '08:00:00');
    expect($status)->toBe('Terlambat');

    $status = GeofenceService::status('09:00:00', '08:00:00');
    expect($status)->toBe('Terlambat');
});
