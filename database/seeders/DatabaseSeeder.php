<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Standard User',
                'role' => User::ROLE_STANDARD,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
