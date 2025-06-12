<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function getRoles(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $roles = Role::select('id', 'name', 'created_at')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'created_at' => $role->created_at->format('Y-m-d'),
            ];
        });

        return response()->json($roles);
    }

    public function index()
    {
        $roles = Role::all();
        return view('user.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('user.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);
        Role::create(['name' => $request->name]);
        return redirect()->route('user.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('user.roles.create', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->name]);
        return redirect()->route('user.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['message' => 'Role deleted successfully.'], 200);
        }

        return redirect()->route('user.roles.index')->with('success', 'Role deleted successfully.');
    }
}