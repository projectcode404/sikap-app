<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat roles dengan guard 'web'
        $roles = [
            'admin',
            'manager',
            'employee'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // 2. (Opsional) Buat permissions default
        $permissions = [
            'manage users',
            'manage employees',
            'view dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web'
            ]);
        }

        // 3. Assign permissions ke role admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->syncPermissions($permissions);

        // 4. Buat users
        $users = [
            [
                'name' => 'superadmin',
                'email' => 'superadmin@sikap-app.dev',
                'password' => 'SuperAdmin1',
                'role' => 'admin'
            ],
            [
                'name' => 'supermanager',
                'email' => 'supermanager@sikap-app.dev',
                'password' => 'SuperManager1',
                'role' => 'manager'
            ],
            [
                'name' => 'employeeuser',
                'email' => 'employee@sikap-app.dev',
                'password' => 'Employee123',
                'role' => 'employee'
            ]
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate([
                'email' => $u['email']
            ], [
                'id' => Str::uuid(),
                'employee_id' => null,
                'name' => $u['name'],
                'password' => Hash::make($u['password']),
                'status' => 'active',
            ]);

            $user->assignRole($u['role']);
        }
    }
}
