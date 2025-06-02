<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $employees = [

            // GENERAL AFFAIRS & HR
            ['id' => '31005502', 'full_name' => 'ARIES TRI WAHONO'],
            ['id' => '50053569', 'full_name' => 'MARIA IKA KUSUMANINGRUM'],
            ['id' => '50088776', 'full_name' => 'IVAN YANUAR LIPESIK'],
            ['id' => '50163456', 'full_name' => 'THORIQ DIFANDRA'],
            ['id' => '50168845', 'full_name' => 'MUHAMMAD REYNALDI BAYHAQI'],
            ['id' => '50106589', 'full_name' => 'ERENS ROBERT SEPTIAN'],
            ['id' => '31005027', 'full_name' => 'NI KETUT BUDHI SASMINI'],
            ['id' => '31007891', 'full_name' => 'NUR FARIDA'],
            ['id' => '50145257', 'full_name' => 'MOCH. KHOIRUL ANAM, S.E'],
            ['id' => '50028526', 'full_name' => 'ASWIN WIDYA PUTRA'],
            ['id' => '50028527', 'full_name' => 'ERY NURHUDI PRAYOGO, SH'],
            ['id' => '50125909', 'full_name' => 'SUETA APRILIA YOHANI'],
            ['id' => '31007148', 'full_name' => 'ANIS KRISWANTONO'],
            ['id' => '31008292', 'full_name' => 'RUDI CAHYONO'],
            ['id' => '50086688', 'full_name' => 'IMRON'],
            ['id' => '31002033', 'full_name' => 'SRI KUNCOROWATI'],
            ['id' => '50053776', 'full_name' => 'LILIEK HERAWATI'],
            ['id' => '31001992', 'full_name' => 'INDAH'],
            ['id' => '31003062', 'full_name' => 'ADI SUWIGNYO'],
            ['id' => '31003793', 'full_name' => 'MINARNO'],
            ['id' => '31008709', 'full_name' => 'LUCKMAN JEFRI'],
            ['id' => '31005946', 'full_name' => 'TIRTO MUKTI'],
            ['id' => '31007582', 'full_name' => 'SWASONO RAHARJO'],
            ['id' => '31004398', 'full_name' => 'RAHEL SIMAMORA'],
            ['id' => '31002651', 'full_name' => 'MUHAMAD ZARKASI'],
            ['id' => '31005378', 'full_name' => 'TJATUR HANDOKO KURNIAWAN'],
            ['id' => '31005448', 'full_name' => 'FAJRI HAZNAN YANUAR'],
            ['id' => '31005632', 'full_name' => 'ELIF DWI CAHYA'],
            ['id' => '50053564', 'full_name' => 'YAYAN BAYU SETIAWAN'],
            ['id' => '31004578', 'full_name' => 'SUTRIMAN SUGIARTO'],
            ['id' => '31000542', 'full_name' => 'NURUL AINAINI'],
            ['id' => '31002344', 'full_name' => 'ACHMAD ZAINURI'],
            ['id' => '31007113', 'full_name' => 'DJOKO POERWANTORO'],
            ['id' => '31008158', 'full_name' => 'HENDRA WAHYUDI'],
            ['id' => '31008216', 'full_name' => 'ARIO TRI KUSUMA YUDHA, SE'],
            ['id' => '50013967', 'full_name' => 'WULAN SURYANTI'],
            ['id' => '50053578', 'full_name' => 'DYAH RETNO PALUPI'],
            ['id' => '50142905', 'full_name' => 'DWIKY ADI DARMA'],
            ['id' => '50103720', 'full_name' => 'M. HAIDZAR HILMI'],
            ['id' => '31000870', 'full_name' => 'ANIK ARIANITA'],
            ['id' => '50088151', 'full_name' => 'MOH HASAN BISRI'],
        ];

        foreach ($employees as $emp) {
            DB::table('employees')->updateOrInsert(
                ['id' => $emp['id']],
                [
                    'full_name'         => $emp['full_name'],
                    'address'           => 'Jl. Rungkut Industri Raya No. 1, Rungkut, Kec. Gunung Anyar, Kota SBY, Jawa Timur 60293',
                    'birth_place'       => 'Surabaya',
                    'birth_date'        => '1990-01-01',
                    'gender'            => 'male',
                    'religion'          => 'islam',
                    'phone'             => null,
                    'email'             => null,
                    'ktp_number'        => null,
                    'npwp_number'       => null,
                    'bpjs_health'       => null,
                    'bpjs_employee'     => null,
                    'education'         => 'S1',
                    'major'             => 'Manajemen',
                    'position_id'       => 6,
                    'division_id'       => null,
                    'work_unit_id'      => 5217218000,
                    'level'             => 'staff',
                    'grade'             => 'b1',
                    'employment_type'   => 'permanent',
                    'vendor'            => 'iap',
                    'in_date'           => '2016-04-01',
                    'retirement_date'   => null,
                    'out_date'          => null,
                    'note'             => null,
                    'status'            => 'active',
                    'photo'             => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]
            );
        }
    }
}
