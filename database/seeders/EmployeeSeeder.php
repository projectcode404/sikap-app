<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::create([
            'id' => 50088776,
            'work_unit_id' => 1,
            'position' => 'General Affair',
            'in_date' => now(),
            'status' => 'active',
        ]);

        User::create([
            'employee_id' => $employee->id,
            'name' => 'Ivan Yanuar Lipesik',
            'email' => 'ivan30yanuar@gmail.com',
            'password' => Hash::make('iapsby50088776'),

        ]);
    }
}
