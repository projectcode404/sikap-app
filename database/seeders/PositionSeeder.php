<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Branch Manager',
            'Oficce Manager',
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
            Position::create(['name' => $position]);
        }
    }
}
