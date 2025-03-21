<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $emailRule = 'nullable|email';
        if ($request->email) {
            $emailRule .= '|unique:users,email,' . $user->id;
        }

        $request->validate([
            'name' => 'required',
            'email' => $emailRule,
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        User::create([
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
