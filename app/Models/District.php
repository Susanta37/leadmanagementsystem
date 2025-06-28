<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';
    protected $primaryKey = 'districtid';
    public $incrementing = true;

    protected $fillable = [
        'district_title',
        'state_id',
        'district_description',
        'district_status',
    ];

    /**
     * Get the state that owns this district.
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    /**
     * Get all active cities for this district.
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'districtid', 'districtid')
                    ->where('status', 'Active');
    }
}
