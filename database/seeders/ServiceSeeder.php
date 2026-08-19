<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Pelayanan Perpustakaan',
            'code' => 'A',
            'description' => 'Pelayanan perpustakaan BPS Kabupaten Kolaka Utara',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Pelayanan Konsultasi',
            'code' => 'B',
            'description' => 'Pelayanan konsultasi data statistik',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Penjualan Produk Statistik',
            'code' => 'C',
            'description' => 'Penjualan produk statistik',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Pelayanan Rekomendasi',
            'code' => 'D',
            'description' => 'Pelayanan rekomendasi kegiatan statistik',
            'is_active' => true,
        ]);
    }
}
