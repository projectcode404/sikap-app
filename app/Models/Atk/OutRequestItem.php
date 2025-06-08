<?php

namespace App\Models\Atk;

use App\Models\Atk\Item;
use App\Models\Atk\OutRequest;
use Illuminate\Database\Eloquent\Model;

class OutRequestItem extends Model
{
    protected $table = 'atk_out_request_items';

    protected $fillable = [
        'atk_out_request_id',
        'atk_item_id',
        'qty',
        'current_stock_at_request',
        'qty_approved',
        'qty_realized',
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