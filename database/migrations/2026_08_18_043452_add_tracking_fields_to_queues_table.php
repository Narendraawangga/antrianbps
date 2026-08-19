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
    Schema::table('queues', function (Blueprint $table) {

        $table->string('public_token')
            ->unique()
            ->nullable()
            ->after('queue_number');

        $table->timestamp('started_at')
            ->nullable()
            ->after('called_at');

        $table->foreignId('served_by')
            ->nullable()
            ->after('started_at')
            ->constrained('users')
            ->nullOnDelete();

    });
}


public function down(): void
{
    Schema::table('queues', function (Blueprint $table) {

        $table->dropForeign(['served_by']);

        $table->dropColumn([
            'public_token',
            'started_at',
            'served_by',
        ]);

    });
}

};
