<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    protected $fillable = [
        'user_id', 'month', 'basic', 'hra', 'allowance', 'deductions', 'net_salary', 'pdf_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

