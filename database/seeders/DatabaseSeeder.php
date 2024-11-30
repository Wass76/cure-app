<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Make sure to import the User model

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create SuperAdmin if it doesn't already exist
        User::firstOrCreate(
            ['phoneNumber' => 'SuperAdmin'],
            [
                'password' => 'Password!1',
                'role' => 'admin',
            ]
        );

        // Create admin if it doesn't already exist
        User::firstOrCreate(
            ['phoneNumber' => 'admin'],
            [
                'password' => 'Password!1',
                'role' => 'admin',
            ]
        );
    }
}
