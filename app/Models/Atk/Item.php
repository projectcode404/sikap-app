<?php

namespace App\Models\Atk;

use App\Models\Atk\Stock;
use App\Models\Atk\ReceiveItem;
use App\Models\Atk\StockAdjustment;
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

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'atk_item_id');
    }

    public function receiveItems()
    {
        return $this->hasMany(ReceiveItem::class, 'atk_item_id');
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class, 'atk_item_id');
    }
}