<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'atk_items';

    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'min_stock',
        'description',
    ];
}