<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\WorkUnit;
use App\Models\Position;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function getEmployees(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $employees = Employee::select('employee_id', 'full_name', 'gender', 'phone', 'level', 'position_id', 'division_id', 'employment_type', 'vendor_name', 'status')
                ->with([
                    'division:id,name',
                    'position:id,name',
                ])
                ->get()
                ->map(function ($employee) {
                    return [
                        'employee_id' => $employee->employee_id,
                        'full_name' => $employee->full_name,
                        'gender' => $employee->gender,
                        'phone' => $employee->phone,
                        'level' => $employee->level,
                        'position' => optional($employee->position)->name ?? '-',
                        'division' => optional($employee->division)->name ?? '-',
                        'employment_type' => $employee->employment_type,
                        'vendor' => $employee->vendor_name,
                        'status' => $employee->status,
                    ];
                });

        return response()->json($employees);
    }

    public function index()
    {
        // $employees = Employee::with('user', 'workUnit', 'position')->get();
        return view('employees.index');
    }
    
    public function create()
    {
        $workUnits = WorkUnit::all();
        $positions = Position::all();
        return view('employees.create', compact('workUnits', 'positions'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'level' => 'required|in:operative,staff,supervisor,manager',
            'employment_type' => 'required|in:permanent,contract',    
        ]);

        $employee = Employee::create([
            'employee_id' => $request->employee_id,
            'full_name' => $request->full_name,
            'address' => $request->address,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' =>$request->education,
            'position_id' => $request->position_id,
            'employment_type' => $request->employment_type,
            'vendor' => $request->vendor,
            'in_date' => $request->in_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.create')->with('success', 'Employee & User created successfully.');
    }
    
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $workUnits = WorkUnit::all();
        $positions = Position::all();
        return view('employees.edit', compact('employee', 'workUnits', 'positions'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'level' => 'required|in:operative,staff,supervisor,manager',
            'employment_type' => 'required|in:permanent,contract',    
        ]);

        $employee->update([
            'employee_id' => $request->employee_id,
            'full_name' => $request->full_name,
            'address' => $request->address,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' =>$request->education,
            'position_id' => $request->position_id,
            'employment_type' => $request->employment_type,
            'vendor' => $request->vendor,
            'in_date' => $request->in_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.edit')->with('success', 'Employee updated successfully.');
    }
    
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
