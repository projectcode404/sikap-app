<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $table = 'work_units';

    protected $fillable = [
        'name',
        'location',
        'type'
    ];
}
