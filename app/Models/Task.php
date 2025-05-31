<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model

{
    use HasFactory;
   protected $fillable = [
        'team_lead_id',
        'employee_id',
        'title',
        'progress',
        'priority',
        'activity_timeline',
        'assigned_date',
        'due_date',
        'attachments',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string', // Enum: pending, in_progress, completed
    ];

    // Team Lead who assigned the task
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    // Employee assigned to the task (nullable for bulk tasks)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Notifications related to this task
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'task_id');
    }
}
