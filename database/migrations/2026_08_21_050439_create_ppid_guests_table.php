<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_guests', function (Blueprint $table) {

            $table->id();

            $table->date('tanggal');

            $table->string('nama');

            $table->string('whatsapp', 20);

            $table->string('pekerjaan');

            $table->text('alamat');

            $table->text('asal_instansi');

            $table->text('tujuan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_guests');
    }
};
