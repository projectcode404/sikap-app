<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $users = User::select('id', 'employee_id', 'name', 'email', 'status')
                ->with('roles:id,name')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'employee_id' => $user->employee_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->roles->pluck('name')->implode(', '),
                        'status' => $user->status,
                    ];
                });

        return response()->json($users);
    }
    
    public function index()
    {
        return view('users.index');
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $emailRule = 'nullable|email|unique:users,email';

        $request->validate([
            'name' => 'required',
            'email' => $emailRule,
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'employee_id' => null,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name');
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $emailRule = 'nullable|email';
        if ($request->email) {
            $emailRule .= '|unique:users,email,' . $user->id;
        }

        $request->validate([
            'name' => 'required|unique:users,name,' . $user->id,
            'email' => $emailRule,
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
