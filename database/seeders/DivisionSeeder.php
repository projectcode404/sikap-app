<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Noodle',
            'Non-Noodle',
            'AMDK',
        ];

        foreach ($divisions as $division) {
            Division::create(['name' => $division]);
        }
    }
}
