<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Master\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat semua permissions
        $permissions = [
            'manage-users', 'assign-role',
            'view-employee', 'create-employee', 'edit-employee',
            'view-atk-stock', 'request-atk', 'approve-atk',
            'receive-atk', 'return-atk',
            'upload-atk-excel', 'print-atk-receipt',
            'view-vehicles', 'create-vehicle', 'edit-vehicle', 'assign-driver',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Buat roles
        $roles = ['superadmin', 'general-affair', 'hrd', 'manager', 'employee'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 3. Assign permissions per role
        Role::findByName('superadmin')->syncPermissions(Permission::all());
        Role::findByName('general-affair')->syncPermissions([
            'view-atk-stock', 'request-atk', 'receive-atk', 'return-atk',
            'upload-atk-excel', 'print-atk-receipt',
            'view-vehicles', 'create-vehicle', 'edit-vehicle', 'assign-driver',
        ]);
        Role::findByName('hrd')->syncPermissions([
            'view-employee', 'create-employee', 'edit-employee',
        ]);
        Role::findByName('manager')->syncPermissions([
            'view-atk-stock', 'approve-atk', 'print-atk-receipt',
            'view-vehicles', 'view-employee',
        ]);
        Role::findByName('employee')->syncPermissions([
            'view-atk-stock', 'request-atk', 'return-atk',
        ]);

        $users = [
            [
                'employee_id' => '50088776',
                'password' => 'QWERTY123',
                'role' => 'superadmin'
            ],
            [
                'employee_id' => '50163456',
                'password' => 'IAPSBY50163456',
                'role' => 'general-affair'
            ],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate([
                'employee_id' => $u['employee_id'],
            ], [
                'id' => Str::uuid(),
                'password' => Hash::make($u['password']),
                'status' => 'active',
            ]);

            $user->syncRoles([$u['role']]);
        }
    }
}
