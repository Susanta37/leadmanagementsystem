<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Notification extends Model
{
    
   use HasFactory;
    protected $fillable = [
        'user_id',
        'lead_id',
        'task_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // User receiving the notification
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Related lead (if applicable)
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    // Related task (if applicable)
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
