<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Division;
use App\Models\Position;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'full_name',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'education',
        'phone',
        'email',
        'ktp_number',
        'npwp_number',
        'bpjs_health',
        'bpjs_employee',
        'position_id',
        'division_id',
        'work_unit_id',
        'level',
        'employment_type',
        'vendor_name',
        'in_date',
        'out_date',
        'notes',
        'status',
        'photo',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($employee) {
            if (!User::where('employee_id', $employee->employee_id)->exists()) {
                $role = $employee->level !== 'manager' ? 'employee' : 'manager';

                $user = User::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->full_name . '_' . $employee->employee_id,
                    'email' => $employee->email,
                    'password' => Hash::make('P@ssw0rd'),
                    'status' => 'active',
                ]);

                $user->assignRole($role);
            }
        });

        static::deleting(function ($employee) {
            User::where('employee_id', $employee->employee_id)->delete();
        });
    }
}
