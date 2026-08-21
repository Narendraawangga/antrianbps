<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppid_guests', function (Blueprint $table) {

            $table->string('status')
                ->default('menunggu')
                ->after('tujuan');

            $table->timestamp('called_at')
                ->nullable()
                ->after('status');

            $table->timestamp('completed_at')
                ->nullable()
                ->after('called_at');
        });
    }

    public function down(): void
    {
        Schema::table('ppid_guests', function (Blueprint $table) {

            $table->dropColumn([
                'status',
                'called_at',
                'completed_at',
            ]);
        });
    }
};
