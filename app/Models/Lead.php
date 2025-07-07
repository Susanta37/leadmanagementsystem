<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'team_lead_id',
        'name',
        'phone',
        'email',
        'dob',
        'state',
        'district',
        'city',
        'company_name',
        'lead_amount',
        'salary',
        'success_percentage',
        'expected_month',
        'remarks',
        'status',
        'lead_type',
        'voice_recording',
        'is_personal_lead',
        'turnover_amount',
        'vintage_year',
        'it_return',
        'bank_name',
    ];

    protected $casts = [
        'dob' => 'date',
        'lead_amount' => 'decimal:2',
        'salary' => 'decimal:2',
        'turnover_amount' => 'decimal:2',
        'it_return' => 'decimal:2',
        'success_percentage' => 'integer',
        'vintage_year' => 'integer',
        'status' => 'string',
        'lead_type' => 'string',
        'is_personal_lead' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'is_personal_lead' => true,
    ];

    public function getVoiceRecordingUrlAttribute()
    {
        return $this->voice_recording ? Storage::url($this->voice_recording) : null;
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

  

    public function forwardedHistories()
{
    return $this->hasMany(LeadForwardedHistory::class, 'lead_id');
}

}
