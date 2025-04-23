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
            ['employee_id' => '31005502', 'full_name' => 'ARIES TRI WAHONO'],
            ['employee_id' => '50053569', 'full_name' => 'MARIA IKA KUSUMANINGRUM'],
            ['employee_id' => '50088776', 'full_name' => 'IVAN YANUAR LIPESIK'],
            ['employee_id' => '50163456', 'full_name' => 'THORIQ DIFANDRA'],
            ['employee_id' => '50168845', 'full_name' => 'MUHAMMAD REYNALDI BAYHAQI'],
            ['employee_id' => '50106589', 'full_name' => 'ERENS ROBERT SEPTIAN'],
            ['employee_id' => '31005027', 'full_name' => 'NI KETUT BUDHI SASMINI'],
            ['employee_id' => '31007891', 'full_name' => 'NUR FARIDA'],
            ['employee_id' => '50145257', 'full_name' => 'MOCH. KHOIRUL ANAM, S.E'],
            ['employee_id' => '50028526', 'full_name' => 'ASWIN WIDYA PUTRA'],
            ['employee_id' => '50028527', 'full_name' => 'ERY NURHUDI PRAYOGO, SH'],
            ['employee_id' => '50125909', 'full_name' => 'SUETA APRILIA YOHANI'],
            ['employee_id' => '31007148', 'full_name' => 'ANIS KRISWANTONO'],
            ['employee_id' => '31008292', 'full_name' => 'RUDI CAHYONO'],
            ['employee_id' => '50086688', 'full_name' => 'IMRON'],
            ['employee_id' => '31002033', 'full_name' => 'SRI KUNCOROWATI'],
            ['employee_id' => '50053776', 'full_name' => 'LILIEK HERAWATI'],
            ['employee_id' => '31001992', 'full_name' => 'INDAH'],
            ['employee_id' => '31003062', 'full_name' => 'ADI SUWIGNYO'],
            ['employee_id' => '31003793', 'full_name' => 'MINARNO'],
            ['employee_id' => '31008709', 'full_name' => 'LUCKMAN JEFRI'],
            ['employee_id' => '31005946', 'full_name' => 'TIRTO MUKTI'],
            ['employee_id' => '31007582', 'full_name' => 'SWASONO RAHARJO'],
            ['employee_id' => '31004398', 'full_name' => 'RAHEL SIMAMORA'],
            ['employee_id' => '31002651', 'full_name' => 'MUHAMAD ZARKASI'],
            ['employee_id' => '31005378', 'full_name' => 'TJATUR HANDOKO KURNIAWAN'],
            ['employee_id' => '31005448', 'full_name' => 'FAJRI HAZNAN YANUAR'],
            ['employee_id' => '31005632', 'full_name' => 'ELIF DWI CAHYA'],
            ['employee_id' => '50053564', 'full_name' => 'YAYAN BAYU SETIAWAN'],
            ['employee_id' => '31004578', 'full_name' => 'SUTRIMAN SUGIARTO'],
            ['employee_id' => '31000542', 'full_name' => 'NURUL AINAINI'],
            ['employee_id' => '31002344', 'full_name' => 'ACHMAD ZAINURI'],
            ['employee_id' => '31007113', 'full_name' => 'DJOKO POERWANTORO'],
            ['employee_id' => '31008158', 'full_name' => 'HENDRA WAHYUDI'],
            ['employee_id' => '31008216', 'full_name' => 'ARIO TRI KUSUMA YUDHA, SE'],
            ['employee_id' => '50013967', 'full_name' => 'WULAN SURYANTI'],
            ['employee_id' => '50053578', 'full_name' => 'DYAH RETNO PALUPI'],
            ['employee_id' => '50142905', 'full_name' => 'DWIKY ADI DARMA'],
            ['employee_id' => '50103720', 'full_name' => 'M. HAIDZAR HILMI'],
            ['employee_id' => '31000870', 'full_name' => 'ANIK ARIANITA'],
            ['employee_id' => '50088151', 'full_name' => 'MOH HASAN BISRI'],
        ];

        foreach ($employees as $emp) {
            DB::table('employees')->updateOrInsert(
                ['employee_id' => $emp['employee_id']],
                [
                    'full_name'         => $emp['full_name'],
                    'address'           => 'Jl. Rungkut Industri Raya No. 1, Rungkut, Kec. Gunung Anyar, Kota SBY, Jawa Timur 60293',
                    'birth_place'       => 'Surabaya',
                    'birth_date'        => '1990-01-01',
                    'gender'            => 'Male',
                    'religion'          => 'Islam',
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
                    'work_unit_id'      => 5217257000,
                    'level'             => 'staff',
                    'grade'             => 'B1',
                    'employment_type'   => 'permanent',
                    'vendor'            => 'IAP',
                    'in_date'           => '2016-04-01',
                    'retirement_date'   => null,
                    'out_date'          => null,
                    'notes'             => null,
                    'status'            => 'active',
                    'photo'             => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]
            );
        }
    }
}
