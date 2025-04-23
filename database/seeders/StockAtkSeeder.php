<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockAtkSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Bolpen Hitam', 'Bolpen Biru', 'Bolpen Merah',
            'Isi staples Kecil', 'Isi Staples Besar',
            'Staples Kecil', 'Staples Besar',
            'Kwitansi', 'Buku Tulis', 'Lakban Besar',
            'Isolasi Kecil', 'Stipo Cair', 'Stipo Kertas',
            'Cutter Besar', 'Isi Cutter', 'Gunting',
            'Penggaris', 'Kresek', 'Tinta Biru', 'Tinta Merah',
            'Spidol Besar Permanent', 'Spidol Besar Boardmaker',
            'Lem Povinal Kecil', 'Lem Povinal Besar 500ml',
            'Karet Gelang', 'Amplop C', 'Amplop D', 'Amplop E',
        ];

        foreach ($items as $item) {
            DB::table('stock_atk')->updateOrInsert(
                ['name' => $item],
                [
                    'unit' => 'pcs',
                    'stock_qty' => rand(20, 100), // ✅ stok random antara 10–100
                    'min_stock' => rand(5, 10),   // ✅ minimal stok juga random
                    'description' => null,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}
