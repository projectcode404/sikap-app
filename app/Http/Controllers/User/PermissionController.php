<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function getPermissions(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $permissions = Permission::select('id', 'name', 'created_at')->get()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'created_at' => $permission->created_at->format('Y-m-d'),
            ];
        });

        return response()->json($permissions);
    }
    
    public function index()
    {
        $permissions = Permission::all();
        return view('user.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('user.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions']);
        Permission::create(['name' => $request->name]);
        return redirect()->route('user.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('user.permissions.create', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        return redirect()->route('user.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['message' => 'Permission deleted successfully.'], 200);
        }

        return redirect()->route('user.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}