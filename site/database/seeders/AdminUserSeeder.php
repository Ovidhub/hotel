<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Hotel Benizia Admin',
            'email'    => 'admin@hotelbenizia.ng',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
