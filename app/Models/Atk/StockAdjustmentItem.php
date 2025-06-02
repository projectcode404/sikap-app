<?php

namespace App\Models\Atk;

use App\Models\Atk\Item;
use App\Models\Atk\StockAdjustment;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    protected $table = 'atk_stock_adjustment_items';

    protected $fillable = [
        'atk_stock_adjustment_id',
        'atk_item_id',
        'adjustment_qty',
        'reason_type',
        'note',
    ];

    public function adjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'atk_stock_adjustment_id');
    }

    public function atkItem()
    {
        return $this->belongsTo(Item::class, 'atk_item_id');
    }
}