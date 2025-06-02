<?php

namespace App\Models\Atk;

use App\Models\User\User;
use App\Models\Atk\StockAdjustmentItem;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $table = 'atk_stock_adjustments';

    protected $fillable = [
        'date',
        'note',
        'adjusted_by',
    ];

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class, 'atk_stock_adjustment_id');
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}