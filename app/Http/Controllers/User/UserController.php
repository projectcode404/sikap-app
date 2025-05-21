<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Master\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $users = User::select('id', 'employee_id', 'status')
                ->with(['roles:id,name','employee:id,full_name'])
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'employee_id' => $user->employee_id,
                        'full_name' => optional($user->employee)->full_name ?? '-',
                        'role' => $user->roles->pluck('name')->implode(', '),
                        'status' => $user->status,
                    ];
                });

        return response()->json($users);
    }

    public function getAvailableEmployees(Request $request)
    {
        if (!$request->ajax() && !$request->has('select') && !$request->has('q')) {
            return abort(404, 'Not Found');
        }

        $search = $request->q;

        $employees = Employee::where('status', 'active')
            ->whereNotIn('id', function ($query) {
                $query->select('employee_id')->from('users')->whereNotNull('employee_id');
            })
            ->when($search, function ($q) use ($search) {
                $q->where('id', 'ILIKE', "%$search%")
                ->orWhere('full_name', 'ILIKE', "%$search%");
            })
            ->limit(20)
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'text' => "{$e->id} - {$e->full_name}",
                ];
            });

        return response()->json($employees);
    }
    
    public function index()
    {
        return view('user.users.index');
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('user.users.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.users.create', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Cek apakah employee_id sudah dipakai user
        if (User::where('employee_id', $request->employee_id)->exists()) {
            return redirect()->back()->withErrors([
                'employee_id' => 'User with this employee ID is already exists.',
            ]);
        }

        $employee = Employee::where('id', $request->employee_id)->firstOrFail();

        // Default password
        $password = 'Iapsby' . $employee->id;

        $user = User::create([
            'id' => Str::uuid(),
            'employee_id' => $employee->id,
            'password' => Hash::make($password),
            'status' => 'active',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('user.users.index')->with('success', 'Successfully created new user.');
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'password' => 'nullable|min:6|confirmed',
            'status' => 'required|in:active,inactive',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Update status
        $user->status = $request->status;

        $user->save();

        // Update roles
        $user->syncRoles($request->roles);

        return redirect()->route('user.users.index')->with('success', 'Successfully updated user.');
    }

    public function resetPassword(User $user)
    {
        $employeeId = $user->employee_id;

        if (!$employeeId) {
            return redirect()->back()->with('error', 'Employee ID not found.');
        }

        $defaultPassword = 'Iapsby' . $employeeId;
        $user->update([
            'password' => Hash::make($defaultPassword),
        ]);

        return redirect()->route('user.users.index')->with('success', 'Successfully reset '.$user->employee_id.' password to default.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['message' => 'User deleted successfully.'], 200);
        }

        return redirect()->route('user.users.index')->with('success', 'User deleted successfully.');
    }
}
