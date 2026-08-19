<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            // Layanan yang dipilih pengunjung
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Nomor antrean, contoh: A-001
            $table->string('queue_number');

            // Lokasi foto pengunjung
            $table->string('photo')->nullable();

            // Status antrean
            $table->enum('status', [
                'waiting',
                'called',
                'serving',
                'completed',
                'skipped'
            ])->default('waiting');

            // Waktu dipanggil
            $table->timestamp('called_at')->nullable();

            // Waktu selesai
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
