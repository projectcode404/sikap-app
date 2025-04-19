<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $managerRole = Role::where('name', 'manager')->firstOrFail();
        $employeeRole = Role::where('name', 'employee')->firstOrFail();
        
        $superAdmin = User::firstOrCreate([
            'id' => Str::uuid(),
            'employee_id' => null,
            'name' => 'superadmin',
            'email' => 'superadmin@sikap-app.dev',
            'password' => Hash::make('SuperAdmin1'),
            'status' => 'active',
        ]);
        $superAdmin->assignRole($adminRole);

        $superManager = User::firstOrCreate([
            'id' => Str::uuid(),
            'employee_id' => null,
            'name' => 'supermanager',
            'email' => 'supermanager@sikap-app.dev',
            'password' => Hash::make('SuperManager1'),
            'status' => 'active',
        ]);
        $superManager->assignRole($managerRole);

        $employee = User::firstOrCreate([
            'id' => Str::uuid(),
            'employee_id' => null,
            'name' => 'employeeuser',
            'email' => 'employee@sikap-app.dev',
            'password' => Hash::make('Employee123'),
            'status' => 'active',
        ]);
        $employee->assignRole($employeeRole);
    }
}
