<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAtk extends Model
{
    use HasFactory;

    protected $table = 'stock_atk';

    protected $fillable = [
        'name',
        'unit',
        'stock_qty',
        'min_stock',
        'description',
    ];
}