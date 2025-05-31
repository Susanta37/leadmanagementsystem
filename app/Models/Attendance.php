<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
   use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'check_in_coordinates',
        'check_out_coordinates',
        'notes',
        'checkin_image',
        'checkout_image',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    // Employee associated with this attendance record
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
