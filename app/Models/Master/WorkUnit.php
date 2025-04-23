<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_unit_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'work_unit_id',
        'name',
        'type'
    ];
}
