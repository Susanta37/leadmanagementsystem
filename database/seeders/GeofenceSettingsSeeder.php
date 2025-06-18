<?php

namespace Database\Seeders;

use App\Models\GeofenceSettings;
use Illuminate\Database\Seeder;

class GeofenceSettingsSeeder extends Seeder
{
    public function run()
    {
        GeofenceSettings::create([
            'office_name' => 'Nexpro Solution Office',
            'latitude' => 20.296899,
            'longitude' => 85.861235, // Example coordinates for Bhubaneswar, India
            'radius' => 100, 
        ]);
    }
}