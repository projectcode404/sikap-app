<?php

namespace App\Models\Atk;

use App\Models\Atk\PurchaseOrderItem;
use App\Models\Master\Supplier;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'atk_purchase_orders';

    protected $fillable = [
        'po_number',
        'po_date',
        'schedule_date',
        'supplier_id',
        'note',
        'status',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'atk_purchase_order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}