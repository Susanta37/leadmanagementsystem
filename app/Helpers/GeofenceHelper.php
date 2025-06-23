<?php

namespace App\Helpers;

class GeofenceHelper
{
    /**
     * Calculate distance between two coordinates using the Haversine formula.
     * 
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @return float Distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Check if a coordinate is within the geofence.
     * 
     * @param float $userLat User's latitude
     * @param float $userLon User's longitude
     * @param float $officeLat Office latitude
     * @param float $officeLon Office longitude
     * @param float $radius Radius in meters
     * @return bool
     */
    public static function isWithinGeofence($userLat, $userLon, $officeLat, $officeLon, $radius)
    {
        $distance = self::calculateDistance($userLat, $userLon, $officeLat, $officeLon);
        return $distance <= $radius;
    }
}