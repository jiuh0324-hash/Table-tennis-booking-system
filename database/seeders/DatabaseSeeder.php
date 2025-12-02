<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TableTennisTable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tabletennis.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@tabletennis.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        TableTennisTable::create([
            'name' => 'Table 1',
            'description' => 'Professional competition table',
            'is_available' => true,
        ]);

        TableTennisTable::create([
            'name' => 'Table 2',
            'description' => 'Standard recreational table',
            'is_available' => true,
        ]);

        TableTennisTable::create([
            'name' => 'Table 3',
            'description' => 'Outdoor weather-resistant table',
            'is_available' => false,
        ]);
    }
}