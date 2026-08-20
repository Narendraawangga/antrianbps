<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Queue extends Model
{
    protected $fillable = [
        'service_id',
        'queue_number',
        'public_token',
        'photo',
        'status',
        'called_at',
        'started_at',
        'served_by',
        'completed_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI USER / PETUGAS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'served_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI PETUGAS YANG MELAYANI
    |--------------------------------------------------------------------------
    */

    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'served_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI LAYANAN
    |--------------------------------------------------------------------------
    */

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'service_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS LABEL
    |--------------------------------------------------------------------------
    */

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {

                'waiting' =>
                'Menunggu',

                'called' =>
                'Dipanggil',

                'serving' =>
                'Sedang Dilayani',

                'completed' =>
                'Selesai',

                'skipped' =>
                'Dilewati',

                default =>
                'Tidak Diketahui',
            }
        );
    }
}
