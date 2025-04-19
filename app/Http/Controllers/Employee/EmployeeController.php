<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Employee\WorkUnit;
use App\Models\Employee\Position;
use App\Models\Employee\Division;
use App\Models\User;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function getEmployees(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $employees = Employee::select('id', 'employee_id', 'full_name', 'gender', 'phone', 'level', 'position_id', 'division_id', 'employment_type', 'vendor_name', 'status')
                ->with([
                    'division:id,name',
                    'position:id,name',
                ])
                ->get()
                ->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'employee_id' => $employee->employee_id,
                        'full_name' => $employee->full_name,
                        'gender' => $employee->gender,
                        'phone' => $employee->phone,
                        'level' => $employee->level,
                        'position' => optional($employee->position)->name ?? '-',
                        'division' => optional($employee->division)->name ?? '-',
                        'employment_type' => $employee->employment_type,
                        'vendor_name' => $employee->vendor_name,
                        'status' => $employee->status,
                    ];
                });

        return response()->json($employees);
    }

    public function index()
    {
        return view('employee.employees.index');
    }
    
    public function create()
    {
        $positions = Position::all();
        $divisions = Division::all();
        $work_units = WorkUnit::all();
        return view('employee.employees.form', compact('positions', 'divisions', 'work_units'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'full_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'religion' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'level' => 'required|in:operative,staff,supervisor,manager',
            'employment_type' => 'required|in:permanent,contract',
            'in_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',    
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
            'vendor_name' => $request->vendor_name,
            'in_date' => $request->in_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.create')->with('success', 'Employee & User created successfully.');
    }
    
    public function show(Employee $employee)
    {
        return view('employee.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $workUnits = WorkUnit::all();
        $positions = Position::all();
        return view('employee.employees.edit', compact('employee', 'workUnits', 'positions'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'full_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'religion' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'level' => 'required|in:operative,staff,supervisor,manager',
            'employment_type' => 'required|in:permanent,contract',
            'in_date' => 'nullable|date',  
            'status' => 'required|in:active,inactive',
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
            'vendor_name' => $request->vendor_name,
            'in_date' => $request->in_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.edit', $employee->id)->with('success', 'Employee updated successfully.');
    }
    
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function exportForm()
    {
        $divisions = Division::all();
        $positions = Position::all();
        return view('employee.employees.export', compact('divisions', 'positions'));
    }

    public function exportExcel(Request $request)
    {
        $query = Employee::with(['division', 'position', 'workUnit']);

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return Excel::download(new EmployeeExport($query->get()), 'employees.xlsx');
    }
}
