<?php

namespace App\Models\Atk;

use App\Models\Atk\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjustment extends Model
{
    use HasFactory;

    protected $table = 'atk_stock_adjustments';

    protected $fillable = [
        'atk_item_id',
        'adjustment_qty',
        'reason_type',
        'note',
        'date',
        'adjusted_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'atk_item_id');
    }

    public function adjustor()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}