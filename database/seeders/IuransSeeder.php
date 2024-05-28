<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IuransSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            DB::table('iurans')->insert([
                'id_warga' => 1,
                'bulan' => '2024-0' . $i . '-01',
                'jumlah_iuran' => 100000,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        for ($i = 1; $i <= 4; $i++) {
            DB::table('iurans')->insert([
                'id_warga' => 2,
                'bulan' => '2024-0' . $i . '-01',
                'jumlah_iuran' => 100000,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
