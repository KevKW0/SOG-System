<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
            'id' => 1,
            'company_name' => 'SOG System',
            'address' => 'Jl. Rangga Gading No.01, Gudang, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16123',
            'phone' => '081234567',
            'path_logo' => '/img/sog.png'
        ]);
    }
}
