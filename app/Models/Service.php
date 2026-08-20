<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];


    public function queues(): HasMany
    {
        return $this->hasMany(
            Queue::class
        );
    }


    public function users(): HasMany
    {
        return $this->hasMany(
            User::class
        );
    }


    public function schedules()
    {
        return $this->hasMany(
            Schedule::class,
            'service_id'
        );
    }
}