<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_lead_id',
        'title',
        'target_type',
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
        'status' => 'string',
        'assigned_date' => 'datetime',
        'due_date' => 'datetime',
        'attachments' => 'array',
        'activity_timeline' => 'array', // Optional: if activity_timeline is JSON
    ];

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'task_id');
    }


 public function list()
{
    $leadId = auth()->id();

    $tasks = Task::where('team_lead_id', $leadId)
        ->with(['assignees:id,name,avatar']) // define relation first
        ->get();

    return response()->json($tasks);
}

 public function assignees()
    {
        return $this->belongsToMany(User::class, 'notifications', 'task_id', 'user_id')
                    ->withPivot('message', 'is_read')
                    ->withTimestamps();
    }

    public function assigned_user()
{
    return $this->belongsTo(User::class, 'assigned_to'); // 'assigned_to' is the foreign key
}

}

