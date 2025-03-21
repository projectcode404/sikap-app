<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkUnit;

class WorkUnitSeeder extends Seeder
{
    public function run(): void
    {
        WorkUnit::create([
            'work_unit_id' => 5217257091,
            'name' => 'Surabaya HCO',
            'type' => 'stock_point',
        ]);
    }
}
