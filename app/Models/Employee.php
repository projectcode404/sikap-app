<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'position_id',
        'division_id',
        'work_unit_id',
        'level',
        'employment_type',
        'vendor_name',
        'in_date',
        'out_date',
        'status',
        'photo',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($employee) {
            $role = $employee->level;

            $user = User::create([
                'employee_id' => $employee->id,
                'name' => null,
                'email' => null, 
                'password' => Hash::make('P@ssw0rd'),
                'role' => $role !=='manager' ? 'employee' : $role,
                'status' => 'active',
            ]);
        });
    }
}
