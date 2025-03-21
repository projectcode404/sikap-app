<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'view reports']);

        $admin->givePermissionTo(['manage users', 'manage employees', 'view reports']);
        $manager->givePermissionTo(['view reports']);
        $employee->givePermissionTo(['view reports']);
    }
}
