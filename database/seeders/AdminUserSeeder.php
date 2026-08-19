<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator Utama',
            'username' => 'admin',
            'password' => 'admin12345',
            'role' => 'admin_utama',
            'is_active' => true,
        ]);
    }
}
