<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User\User;
use App\Models\Master\WrokUnit;
use App\Models\Master\Division;
use App\Models\Master\Position;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'full_name',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'phone',
        'email',
        'ktp_number',
        'npwp_number',
        'bpjs_health',
        'bpjs_employee',
        'education',
        'major',
        'position_id',
        'division_id',
        'work_unit_id',
        'level',
        'employment_type',
        'vendor',
        'in_date',
        'retirement_date',
        'out_date',
        'note',
        'status',
        'photo',
    ];

    public function user() {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    public function workUnit() {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id', 'id');
    }

    public function division() {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function position() {
        return $this->belongsTo(Position::class, 'position_id');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::created(function ($employee) {
    //         if (!User::where('employee_id', $employee->employee_id)->exists()) {
    //             $role = $employee->level !== 'manager' ? 'employee' : 'manager';

    //             $user = User::create([
    //                 'id' => Str::uuid(),
    //                 'employee_id' => $employee->employee_id,
    //                 'name' => $employee->full_name . '_' . $employee->employee_id,
    //                 'email' => $employee->email,
    //                 'password' => Hash::make('P@ssw0rd'),
    //                 'status' => 'active',
    //             ]);

    //             $user->assignRole($role);
    //         }
    //     });

    //     static::deleting(function ($employee) {
    //         User::where('employee_id', $employee->employee_id)->delete();
    //     });
    // }
}
