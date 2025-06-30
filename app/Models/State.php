<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'state';
    protected $primaryKey = 'state_id';
    public $incrementing = true;

    protected $fillable = [
        'state_title',
        'state_description',
        'status',
    ];

    /**
     * Get all active districts for this state.
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'state_id', 'state_id')
                    ->where('district_status', 'Active');
    }
}
?>