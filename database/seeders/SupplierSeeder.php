<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'PT Otani Premium Paper Industry',
                'pic' => 'Budi Santoso',
                'email' => 'budi@otani.co.id',
                'phone' => '021-89123456',
                'address' => 'Jl. Industri Raya I No.6 Blok A4/10 Kelurahan Jakate, Kec. Jatiuwung, Kota Tangerang, Banten 15135',
                'bank_name' => null,
                'bank_account' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Mulia Form Grafindoy',
                'pic' => 'Fina',
                'email' => 'fina@muliaform.co.id',
                'phone' => '021-3518853',
                'address' => 'Jl. Tanah Abang III/21A Jakarta Pusat, DKI Jakarta 10160',
                'bank_name' => null,
                'bank_account' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
