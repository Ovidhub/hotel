<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Hotel Benizia Admin',
            'email'    => 'admin@hotelbenizia.ng',
            'password' => Hash::make('password'),
        ]);

        // is_admin is not in $fillable (to prevent mass-assignment); set explicitly
        $user->is_admin = true;
        $user->save();
    }
}
