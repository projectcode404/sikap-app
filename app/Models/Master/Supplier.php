<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pic',
        'phone',
        'email',
        'bank_name',
        'bank_account',
        'address',
        'status',
    ];
}
