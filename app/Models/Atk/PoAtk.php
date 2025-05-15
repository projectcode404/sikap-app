<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoAtk extends Model
{
    use HasFactory;

    protected $table = 'po_atk';

    protected $fillable = [
        'po_number',
        'po_date',
        'schedule_date',
        'supplier_id',
        'note',
        'status',
        'created_by',
    ];
}