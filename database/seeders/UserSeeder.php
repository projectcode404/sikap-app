<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'employee_id' => 1,
            'name' => 'superman',
            'email' => 'superman@localhost.com',
            'password' => Hash::make('supermanisdead'),
            'role' => 'admin',
        ]);
    }
}
