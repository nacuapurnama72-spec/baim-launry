<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            ['nama_layanan' => 'Cuci Kering Kiloan', 'harga_per_kg' => 7000, 'unit' => 'kg', 'estimasi_waktu' => '3-4 hari', 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Cuci Setrika', 'harga_per_kg' => 12000, 'unit' => 'kg', 'estimasi_waktu' => '1-2 hari', 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Express', 'harga_per_kg' => 25000, 'unit' => 'kg', 'estimasi_waktu' => '5 jam', 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Satuan / Bedcover', 'harga_per_kg' => 35000, 'unit' => 'pcs', 'estimasi_waktu' => '2 hari', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
