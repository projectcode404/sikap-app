<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\WorkUnit;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user', 'workUnit', 'position')->get();
        return view('employees.index', compact('employees'));
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
            'name' => $request->name,
            'position_id' => $request->position_id,
            'division_id' => $request->division_id,
            'work_unit_id' => $request->work_unit_id,
            'position_id' => $request->position_id,
            'level' => $request->level,
            'employment_type' => $request->employment_type,
            'in_date' => $request->in_date,
            'vendor_name' => $request->vendor_name,
            'status' => 'active',
            'level' => $request->level,
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
            'name' => $request->name,
            'position_id' => $request->position_id,
            'division_id' => $request->division_id,
            'work_unit_id' => $request->work_unit_id,
            'position_id' => $request->position_id,
            'level' => $request->level,
            'employment_type' => $request->employment_type,
            'in_date' => $request->in_date,
            'vendor_name' => $request->vendor_name,
            'status' => 'active',
            'level' => $request->level,
        ]);

        return redirect()->route('employees.edit')->with('success', 'Employee updated successfully.');
    }
    
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
