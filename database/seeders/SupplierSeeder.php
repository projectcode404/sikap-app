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
                'npwp' => '01.234.567.8-999.000',
                'pic' => 'Budi Santoso',
                'email' => 'budi@otani.co.id',
                'phone' => '02129309975-79',
                'address' => 'Jl. Industri Raya I No.6 Blok A4/10 Kelurahan Jakate, Kec. Jatiuwung, Kota Tangerang, Banten 15135',
                'bank_name' => null,
                'bank_account' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
