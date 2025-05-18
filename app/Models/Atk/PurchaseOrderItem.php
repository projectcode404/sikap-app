<?php

namespace App\Models\Atk;

use App\Models\Atk\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'atk_purchase_order_items';

    protected $fillable = [
        'atk_purchase_order_id',
        'atk_item_id',
        'qty',
        'received_qty',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'atk_purchase_order_id');
    }
}