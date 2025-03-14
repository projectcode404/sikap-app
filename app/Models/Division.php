<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
}
