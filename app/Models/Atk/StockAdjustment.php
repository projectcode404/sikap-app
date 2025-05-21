<?php

namespace App\Models\Atk;

use App\Models\Master\User;
use App\Models\Atk\Item;
use Illuminate\Database\Eloquent\Model;

class AtkStockAdjustment extends Model
{
    protected $table = 'atk_stock_adjustments';

    protected $fillable = [
        'atk_item_id',
        'adjustment_qty',
        'reason_type',
        'note',
        'date',
        'adjusted_by',
    ];

    // Relasi ke item
    public function item()
    {
        return $this->belongsTo(Item::class, 'atk_item_id');
    }

    // Relasi ke user (yang mengoreksi)
    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}