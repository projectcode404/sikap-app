<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'employee_id' => 1001,
            'name' => 'SuperMan',
            'email' => 'superman@example.com',
            'password' => Hash::make('1v4nXC0&3?;'),
            'role' => 'admin',
        ]);
    }
}
