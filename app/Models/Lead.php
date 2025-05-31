<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'team_lead_id',
        'name',
        'phone',
        'email',
        'dob',
        'location',
        'company_name',
        'lead_amount',
        'salary',
        'success_percentage',
        'expected_month',
        'remarks',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'lead_amount' => 'decimal:2',
        'salary' => 'decimal:2',
        'success_percentage' => 'integer',
        'status' => 'string', // Enum: pending, approved, rejected, completed
    ];

    // Employee who created the lead
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Team Lead assigned to review the lead
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    // Notifications related to this lead
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'lead_id');
    }
}