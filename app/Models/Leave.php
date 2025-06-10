<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'applied_to',
        'approved_by',
        'decision_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'decision_date' => 'datetime',
    ];

    // Employee who applied
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Manager or HR to whom it was submitted
    public function appliedTo()
    {
        return $this->belongsTo(User::class, 'applied_to');
    }

    // Person who approved/rejected
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
