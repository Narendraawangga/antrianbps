<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidGuest extends Model
{
    protected $table = 'ppid_guests';

    protected $fillable = [
        'tanggal',
        'nama',
        'whatsapp',
        'pekerjaan',
        'alamat',
        'asal_instansi',
        'tujuan',
        'status',
        'called_at',
        'completed_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
