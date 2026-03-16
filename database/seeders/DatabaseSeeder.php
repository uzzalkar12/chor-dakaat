<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 4 demo players for testing
        $players = [
            ['name' => 'রাহিম', 'email' => 'rahim@test.com'],
            ['name' => 'করিম', 'email' => 'karim@test.com'],
            ['name' => 'সুমাইয়া', 'email' => 'sumaiya@test.com'],
            ['name' => 'তানিয়া', 'email' => 'tania@test.com'],
        ];

        foreach ($players as $player) {
            User::firstOrCreate(
                ['email' => $player['email']],
                [
                    'name'     => $player['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $this->command->info('✅ ৪টি ডেমো ব্যবহারকারী তৈরি হয়েছে (পাসওয়ার্ড: password)');
    }
}
