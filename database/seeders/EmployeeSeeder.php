<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::create([
            'employee_id' => 50088776,
            'name' => 'Ivan Yanuar Lipesik',
            'work_unit_id' => 5217257091,
            'position_id' => 6,
            'level' => 'staff',
            'employment_type' => 'permanent',
            'status' => 'active',
        ]);
    }
}
