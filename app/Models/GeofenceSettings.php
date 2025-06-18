<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeofenceSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        'latitude',
        'longitude',
        'radius',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'float',
    ];
}