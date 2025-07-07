<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadForwardedHistory extends Model
{
    protected $table = 'lead_forwarded_histories';

    protected $fillable = [
        'lead_id',
        'sender_user_id',
        'receiver_user_id',
        'is_forwarded',
        'forwarded_at',
    ];

    // Relationship: this forwarded history belongs to a Lead
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    // Relationship: the sender (who forwarded the lead)
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    // Relationship: the receiver (to whom the lead was forwarded)
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}
