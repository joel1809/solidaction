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
                'name' => 'Aminata Koné',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+225 27 22 41 18 30',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'redaction@solidaction.org'],
            [
                'name' => 'Yao Kouadio',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'phone' => '+225 07 08 12 34 56',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
