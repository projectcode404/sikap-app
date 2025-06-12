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
        'employee_id',
        'position_name',
        'work_unit_id',
        'request_date',
        'period',
        'status',

        'created_by',
        'approved_by',
        'rejected_by',
        'realized_by',
        'completed_by',
        'request_note',
        'approval_note',
        'canceled_reason',

        'receipt_file',
        'approved_at',
        'rejected_at',
        'realized_at',
        'completed_at',
        'canceled_at',
        'printed_at',
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
        return $this->belongsTo(User::class, 'created_by', 'employee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'employee_id');
    }

    public function items()
    {
        return $this->hasMany(OutRequestItem::class, 'atk_out_request_id', 'id');
    }
}