<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Employee;
use App\Models\Master\WorkUnit;
use App\Models\Master\Position;
use App\Models\Master\Division;
use App\Models\User\User;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function getEmployees(Request $request) {
        if (!$request->ajax() && !$request->has('select') && !$request->has('q')) {
            return abort(404, 'Not Found');
        }

        // Tom Select
        if ($request->has('select') || $request->has('q')) {
            $search = $request->q;

            $employees = Employee::query()
                ->where(function ($q) use ($search) {
                    $q->where('id', 'ILIKE', "%$search%")
                    ->orWhere('full_name', 'ILIKE', "%$search%");
                })
                ->limit(20)
                ->select('id', 'full_name')
                ->get()
                ->map(function ($e) {
                    return [
                        'id' => $e->id,
                        'text' => $e->id . ' - ' . $e->full_name,
                    ];
                });

            return response()->json($employees);
        }

        // AG Grid
        $employees = Employee::select('id', 'full_name', 'gender', 'phone', 'level', 'position_id', 'division_id', 'employment_type', 'vendor', 'status')
                ->with([
                    'division:id,name',
                    'position:id,name',
                ])
                ->get()
                ->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'full_name' => $employee->full_name,
                        'gender' => $employee->gender,
                        'phone' => $employee->phone,
                        'level' => $employee->level,
                        'position' => optional($employee->position)->name ?? '-',
                        'division' => optional($employee->division)->name ?? '-',
                        'employment_type' => $employee->employment_type,
                        'vendor' => $employee->vendor,
                        'status' => $employee->status,
                    ];
                });

        return response()->json($employees);
    }

    public function index()
    {
        return view('master.employees.index');
    }
    
    public function create()
    {
        $positions = Position::all();
        $divisions = Division::all();
        $work_units = WorkUnit::all();
        return view('master.employees.form', compact('positions', 'divisions', 'work_units'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:employees,id',
            'full_name' => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'religion' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'position_id' => 'required|exists:positions,id',
            'division_id' => 'nullable|exists:divisions,id',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'education' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:100',
            'level' => 'required|in:operative,staff,supervisor,manager',
            'employment_type' => 'required|in:permanent,contract',
            'vendor' => 'required|in:IAP,OS',
            'grade' => 'nullable|in:A1,A2,A3,B1,B2,B3,C1,C2,C3,D1,D2,D3,E1,E2',
            'in_date' => 'nullable|date',
            'retirement_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  
        ]);

        $employee = Employee::create([
            'id' => $request->id,
            'full_name' => $request->full_name,
            'address' => $request->address,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' =>$request->education,
            'position_id' => $request->position_id,
            'employment_type' => $request->employment_type,
            'vendor' => $request->vendor_name,
            'in_date' => $request->in_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.create')->with('success', 'Employee & User created successfully.');
    }
    
    public function show(Employee $employee)
    {
        return view('master.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $workUnits = WorkUnit::all();
        $positions = Position::all();
        return view('master.employees.edit', compact('employee', 'workUnits', 'positions'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'id' => 'required|unique:employees,id,' . $employee->id,
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
            'id' => $request->id,
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
        return view('master.employees.export', compact('divisions', 'positions'));
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
