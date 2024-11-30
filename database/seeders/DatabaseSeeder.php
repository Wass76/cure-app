<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'phoneNumber' => 'SuperAdmin',
            'password' => 'Password!1',
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'phoneNumber' => 'admin',
            'password' => 'Password!1',
            'role' => 'admin'
        ]);





    }
}
