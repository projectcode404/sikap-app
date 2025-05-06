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
            'Plastik Kresek Bintang kilat 40X65','Standard AE-7 Hitam','Standard AE-7 Biru','Standard AE-7 Merah','Stapler Max HD-10','Stapler Joyko HD-50',
            'Isi Staples Kecil Deli No.10','Isi Staples Besar MAX No.3','Kwitansi PPL 40 TGG','Corr Tape Kenko KE-01','Corr Tape Joyko CT-523','Spidol Besar Permanent Snowman',
            'Spidol Besar Boardmaker Snowman','Spidol Snowman PW1A Hitam','Spidol Snowman PW1A Biru','Spidol Snowman PW1A Merah','Tinta Yamura Gabus Biru','Tinta Yamura Gabus Merah',
            'Joyko Binder Clip 260','Joyko Binder Clip 200','Joyko Binder Clip 155','Joyko Binder Clip 105','Joyko Paper Clip No.5','Joyko Paper Clip No.3','Joyko HL 1 Stabilo Kuning',
            'Joyko HL 4 Stabilo Merah','Joyko HL 5 Stabilo Orange','Isolasi Nachi 1/2 x 25 @12','Lem Povinal Kecil 111','Lem Povinal Besar 113 500ml','Cutter Renteng Jansen besar','Joyko Reff Cutter L-150',
            'Gunting Kecil','Penggaris Butterfly 30cm','Calculator Joyko','Amplop C','Amplop D','Amplop E','Map L Bening','Map Bussiness File','Kertas Telstruk 58x65x12','Pita Telstruk','Acco Paper Fasteners',
            'Karbon Daito','Buku Folio 100 Paperline','Buku Tulis','Karet Gelang Pentil','Bantex Ordner Besar / Folio','Cartridge LQ2180','Cartridge LQ310','Pita Printer LQ2180','Pita Printer LQ310','Kertas Print Canvass',
            'Lakban Besar Bening','Faktur 2ply','1ply Polos','2ply Polos','2ply Potong','4ply Polos','4ply Potong','A4 SIDU 70gsm','Debit Note','SJU','BBT','BBK','BKK','SPB 3ply','Bank Transfer','Nota Retur','1ply 14X7/8X11',
            '3ply RRP 14X7/8X9',
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
