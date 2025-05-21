<?php

namespace App\Models\Atk;

use App\Models\Master\User;
use App\Models\Atk\PurchaseOrder;
use App\Models\Atk\ReceiveItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receive extends Model
{
    use HasFactory;

    protected $table = 'atk_receives';

    protected $fillable = [
        'atk_purchase_order_id',
        'received_by',
        'receive_date',
        'note',
        'receipt_file',
    ];

    // Relasi ke Purchase Order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'atk_purchase_order_id');
    }

    // Relasi ke Items yang diterima
    public function receiveItems()
    {
        return $this->hasMany(ReceiveItem::class, 'atk_receive_id');
    }

    // Relasi ke user (opsional jika model user sudah ada)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}