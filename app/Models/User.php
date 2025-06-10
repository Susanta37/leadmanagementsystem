<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'designation',
        'department',
        'profile_photo',
        'address',
        'pan_card',
        'aadhar_card',
        'signature',
        'created_by',
        'team_lead_id',
    ];

    protected $casts = [
        'designation' => 'string', // Enum: employee, team_lead, operations, admin
        'email_verified_at' => 'datetime',
    ];
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? Storage::url($this->profile_photo) : null;
    }

    // Optionally, modify profile_photo to return the full URL
    public function getProfilePhotoAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
    /**
     * Get the URL for the PAN card.
     *
     * @return string|null
     */
    public function getPanCardUrlAttribute()
    {
        return $this->pan_card ? Storage::url($this->pan_card) : null;
    }

    /**
     * Get the URL for the Aadhar card.
     *
     * @return string|null
     */
    public function getAadharCardUrlAttribute()
    {
        return $this->aadhar_card ? Storage::url($this->aadhar_card) : null;
    }

    /**
     * Get the URL for the signature.
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute()
    {
        return $this->signature ? Storage::url($this->signature) : null;
    }

    // User who created this user (Admin or Team Lead)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Users created by this user (for Admin or Team Lead)
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }
    public function teamMembers()
    {
        return $this->hasMany(User::class, 'team_lead_id');
    }
    // Team Lead of this user (for Employees)
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    // Employees under this Team Lead
    public function employees()
    {
        return $this->hasMany(User::class, 'team_lead_id');
    }

    // Leads created by this user (for Employees)
    public function createdLeads()
    {
        return $this->hasMany(Lead::class, 'employee_id');
    }

    // Leads assigned to this Team Lead for review
    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'team_lead_id');
    }

    // Tasks assigned by this Team Lead
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'team_lead_id');
    }

    // Tasks assigned to this Employee
    public function tasks()
    {
        return $this->hasMany(Task::class, 'employee_id');
    }

    // Attendance records for this Employee
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    // Notifications for this user
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }

    // Leaves received for approval (if this user is HR or team lead)
    public function receivedLeaveRequests()
    {
        return $this->hasMany(Leave::class, 'applied_to');
    }
}
