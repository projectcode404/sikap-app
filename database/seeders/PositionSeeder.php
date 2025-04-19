<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Branch Manager',
            'Office Manager',
            'Sales Manager',
            'Distribution Center Manager',
            'Personalia & General Affair Manager',
            'General Affair', 
            'Personalia & Human Resource', 
            'Finance',
            'Salesman',
            'Stock Point Officer',
            'Stock Point Clerk',
            'Staff Collection & Admin Stock',
            'Sales Service',
            'Picker Packer',
            'Delivery Man',
            'Security'
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['name' => $position]);
        }
    }
}
