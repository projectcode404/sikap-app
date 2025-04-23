<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Exclusive Noodle',
            'Exclusive Noodle Virtual',
            'Exclusive Non-Noodle',
            'Exclusive Non-Noodle Virtual',
            'Exclusive ILO',
            'Exclusive Tepung',
            'CLUB',
            'CLUB Virtual',
            'Canvass',
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate(['name' => $division]);
        }
    }
}
