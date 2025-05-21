<?php

namespace App\Models\Atk;

use App\Models\Atk\Receive;
use App\Models\Atk\PurchaseOrderItem;
use App\Models\Atk\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveItem extends Model
{
    use HasFactory;

    protected $table = 'atk_receive_items';

    protected $fillable = [
        'atk_receive_id',
        'atk_purchase_order_item_id',
        'atk_item_id',
        'qty',
    ];

    // Relasi ke Receive
    public function receive()
    {
        return $this->belongsTo(Receive::class, 'atk_receive_id');
    }

    // Relasi ke PO item
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'atk_purchase_order_item_id');
    }

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'atk_item_id');
    }
}