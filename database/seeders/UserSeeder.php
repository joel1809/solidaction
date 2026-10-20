<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@solidaction.org'],
            [
                'name' => 'Amina Diallo',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+33 1 84 80 12 34',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'redaction@solidaction.org'],
            [
                'name' => 'Marc Lefèvre',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'phone' => '+33 1 84 80 12 35',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
