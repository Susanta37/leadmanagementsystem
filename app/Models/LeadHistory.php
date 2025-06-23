<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'action',
        'status',
        'forwarded_to',
        'comments',
    ];

    protected $casts = [
        'lead_id' => 'integer',
        'user_id' => 'integer',
        'forwarded_to' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forwardedTo()
    {
        return $this->belongsTo(User::class, 'forwarded_to');
    }
}