<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'districtid',
        'state_id',
        'description',
        'status',
    ];

    /**
     * Get the district that owns this city.
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'districtid', 'districtid');
    }

    /**
     * Get the state that owns this city.
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
}
