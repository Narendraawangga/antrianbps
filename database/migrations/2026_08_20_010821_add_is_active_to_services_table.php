<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | NONAKTIFKAN PELAYANAN YANG TIDAK DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        DB::table('services')
            ->where(
                'name',
                'Penjualan Produk Statistik'
            )
            ->update([
                'is_active' => false,
            ]);
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | AKTIFKAN KEMBALI JIKA MIGRATION DI-ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::table('services')
            ->where(
                'name',
                'Penjualan Produk Statistik'
            )
            ->update([
                'is_active' => true,
            ]);
    }
};