<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();

            // Petugas yang mendapat jadwal
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Layanan yang ditangani
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Tanggal piket
            $table->date('date');

            // Jam mulai
            $table->time('start_time');

            // Jam selesai
            $table->time('end_time');

            // Status jadwal
            $table->enum('status', [
                'aktif',
                'selesai',
                'dibatalkan'
            ])->default('aktif');

            // Keterangan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
