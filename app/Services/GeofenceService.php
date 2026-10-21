<?php

namespace App\Services;

use App\Models\OfficeSetting;

class GeofenceService
{
    private const EARTH_RADIUS_METERS = 6371000;

    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round(self::EARTH_RADIUS_METERS * $c);
    }

    public function isWithinRadius(float $userLat, float $userLon, ?OfficeSetting $office = null): bool
    {
        $office ??= OfficeSetting::first();

        if (! $office) {
            return false;
        }

        $distance = $this->calculateDistance(
            $userLat, $userLon,
            (float) $office->latitude,
            (float) $office->longitude,
        );

        return $distance <= $office->radius_meter;
    }

    public function getDistance(float $userLat, float $userLon, ?OfficeSetting $office = null): int
    {
        $office ??= OfficeSetting::first();

        if (! $office) {
            return 0;
        }

        return $this->calculateDistance(
            $userLat, $userLon,
            (float) $office->latitude,
            (float) $office->longitude,
        );
    }

    public static function status(string $clockInTime, string $workStartTime): string
    {
        return $clockInTime <= $workStartTime ? 'Hadir' : 'Terlambat';
    }
}
