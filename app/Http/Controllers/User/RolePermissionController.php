<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('user.roles.permissions', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('user.roles.index')->with('success', 'Permissions updated successfully.');
    }
}