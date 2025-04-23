<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Branch Manager',
            'Office Manager',
            'Sales Manager',
            'Distribution Center Manager',
            'Personnel & General Affair Manager',
            'General Affairs Officer', 
            'Human Resource Officer', 
            'Finance Supervisor',
            'Finance Officer',
            'EDP Supervisor',
            'EDP Officer',
            'Admin Stock Handling Supervisor',
            'Admin Stock Handling Officer',
            'Sales Supervisor',
            'Account Salesman',
            'Canvass Salesman',
            'Canvass Driver',
            'Deliveryman',
            'Delivery Driver',
            'Picker Packer',
            'Stock Point Control Supervisor',
            'Stock Point Controller',
            'Junior Stock Point Officer',
            'Staff Collection & Adm Stock',
            'Security'
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['name' => $position]);
        }
    }
}
