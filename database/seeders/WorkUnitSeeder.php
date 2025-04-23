<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\WorkUnit;

class WorkUnitSeeder extends Seeder
{
    public function run(): void
    {
        $workUnits = [
            ['work_unit_id' => '5217257091', 'name' => 'Surabaya HCO', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257084', 'name' => 'SP Balong Bendo', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257093', 'name' => 'SP Balong Panggang', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257123', 'name' => 'SP Ambunten', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257076', 'name' => 'SP Arosbaya', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257002', 'name' => 'SP Babat', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257094', 'name' => 'SP Bangilan', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257073', 'name' => 'SP Buduran', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257001', 'name' => 'SP Candi', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257028', 'name' => 'SP Brondong', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257066', 'name' => 'SP Bungah', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257048', 'name' => 'SP Bubutan', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257099', 'name' => 'SP Genteng', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257034', 'name' => 'SP Bangil', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257049', 'name' => 'SP Beji', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257141', 'name' => 'SP Driyorejo-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257146', 'name' => 'SP Lidah-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257150', 'name' => 'SP Pamekasan-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257155', 'name' => 'SP Sumenep-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257139', 'name' => 'SP Bedul-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257140', 'name' => 'SP Darmokali-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257154', 'name' => 'SP Sidayu-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257158', 'name' => 'SP Unggul-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257142', 'name' => 'SP Kalimas-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257143', 'name' => 'SP Kalimas Barat-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257151', 'name' => 'SP Pandaan-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257152', 'name' => 'SP Pasuruan-Club', 'type' => 'stock_point'],
            ['work_unit_id' => '5217257086', 'name' => 'DC Surabaya', 'type' => 'dc'],
            ['work_unit_id' => '5217257087', 'name' => 'Depo Bojonegoro', 'type' => 'depo'],
            ['work_unit_id' => '5217257089', 'name' => 'Depo Madura', 'type' => 'depo'],
            ['work_unit_id' => '5217257000', 'name' => 'IAP Surabaya', 'type' => 'office'],
        ];

        foreach ($workUnits as $unit) {
            WorkUnit::firstOrCreate(
                ['work_unit_id' => (string) $unit['work_unit_id']],
                ['name' => $unit['name'], 'type' => $unit['type']]
            );
        }
    }
}
