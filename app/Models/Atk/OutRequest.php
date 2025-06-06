<?php

namespace App\Models\Atk;

use App\Models\Master\Employee;
use App\Models\Master\WorkUnit;
use App\Models\User\User;
use App\Models\Atk\OutRequestItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutRequest extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $table = 'atk_out_requests';

    protected $fillable = [
        'id',
        'id_formester',
        'employee_id',
        'position_name',
        'work_unit_id',
        'request_date',
        'period',
        'created_by',
        'approved_by',
        'status',
        'receipt_file',
        'printed_at',
        'received_at',
        'remarks',
        'canceled_reason',
        'canceled_at',
        'restored_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function items()
    {
        return $this->hasMany(OutRequestItem::class, 'atk_out_request_id', 'id');
    }
}