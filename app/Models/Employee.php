<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_unit_id',
        'position',
        'in_date',
        'out_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
