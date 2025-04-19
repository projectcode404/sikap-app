<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'employee_id' => 50088776,
            'full_name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1, Surabaya',
            'birth_place' => 'Surabaya',
            'birth_date' => '1990-05-15',
            'gender' => 'Male',
            'religion' => 'Islam',
            'education' => 'S1 Teknik Informatika',
            'phone' => '081234567890',
            'email' => 'johndoe@example.com',
            'ktp_number' => '1234567890123456',
            'npwp_number' => '09.876.543.21-000',
            'bpjs_health' => '1234567890',
            'bpjs_employee' => '0987654321',
            'position_id' => 1,
            'division_id' => 2,
            'work_unit_id' => 3,
            'level' => 'staff',
            'employment_type' => 'permanent',
            'vendor_name' => 'PT. Indomarco Adi Prima',
            'in_date' => '2020-01-10',
            'out_date' => null,
            'status' => 'active',
            'photo' => null,
        ]);

        Employee::create([
            'employee_id' => 50053582,
            'full_name' => 'Jane Smith',
            'address' => 'Jl. Sudirman No. 2, Jakarta',
            'birth_place' => 'Jakarta',
            'birth_date' => '1992-08-20',
            'gender' => 'Female',
            'religion' => 'Kristen',
            'education' => 'S2 Management',
            'phone' => '082345678901',
            'email' => 'janesmith@example.com',
            'ktp_number' => '2345678901234567',
            'npwp_number' => '10.987.654.32-000',
            'bpjs_health' => '2345678901',
            'bpjs_employee' => '1098765432',
            'position_id' => 2,
            'division_id' => 1,
            'work_unit_id' => 4,
            'level' => 'manager',
            'employment_type' => 'contract',
            'vendor_name' => 'PT. Sumberdaya Dian Mandiri',
            'in_date' => '2019-07-15',
            'out_date' => null,
            'status' => 'active',
            'photo' => null,
        ]);
    }
}
