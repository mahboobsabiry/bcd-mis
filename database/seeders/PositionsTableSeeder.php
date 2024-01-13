<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->delete();

        $positions = [
            [
                'parent_id' => 0,
                'title'     => 'ریاست گمرک بلخ',
                'code'      => "001",
                'position_number' => 2,
                'num_of_pos' => 1,
                'desc'      => 'مقام ریاست گمرک بلخ'
            ],
            [
                'parent_id' => 1,
                'title'     => 'مدیر اجرائیه',
                'code'      => "002",
                'position_number' => 5,
                'num_of_pos' => 1,
                'desc'      => ''
            ],
            [
                'parent_id' => 1,
                'title'     => 'آمر عملیاتی',
                'code'      => "029",
                'position_number' => 3,
                'num_of_pos' => 1,
                'desc'      => 'آمریت عملیاتی گمرک بلخ'
            ],
            [
                'parent_id' => 1,
                'title'     => 'آمر تخنیکی و مسلکی',
                'code'      => "118",
                'position_number' => 3,
                'num_of_pos' => 1,
                'desc'      => 'آمریت تخنیکی و مسلکی ریاست گمرک بلخ'
            ],
            [
                'parent_id' => 1,
                'title'     => 'آمر گمرک سرحدی حیرتان',
                'code'      => "154",
                'position_number' => 3,
                'num_of_pos' => 1,
                'desc'      => 'آمریت گمرک سرحدی حیرتان واقع لب مرز با کشور اوزبیکستان'
            ],
            [
                'parent_id'     => 1,
                'title'         => 'مدیر عمومی مالی و اداری',
                'code'          => "008",
                'position_number' => 4,
                'num_of_pos' => 1,
                'desc'          => 'مدیریت عمومی اداری ریاست گمرک بلخ'
            ],
            [
                'parent_id'     => 3,
                'title'         => 'مدیر عمومی اسیکودا و سیستم های گمرکی',
                'code'          => "115",
                'position_number' => 4,
                'num_of_pos' => 1,
                'desc'          => 'مدیریت عمومی سیستم ریاست گمرک بلخ'
            ],
            [
                'parent_id'     => 5,
                'title'         => 'مدیر عمومی تشریح اموال',
                'code'          => "172",
                'position_number' => 4,
                'num_of_pos' => 1,
                'desc'          => ' آمریت گمرک سرحدی حیرتان'
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
