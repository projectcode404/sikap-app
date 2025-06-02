<?php

namespace App\Models\Atk;

use App\Models\Atk\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'atk_stocks';

    protected $fillable = [
        'atk_item_id',
        'type',
        'qty',
        'begining_stock',
        'ending_stock',
        'note',
    ];

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'atk_item_id');
    }
}