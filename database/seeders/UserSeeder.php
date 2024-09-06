<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Remove the data
        User::truncate();
        // Admin
        User::create([
            'name' => fake()->name,
            'email' => 'master@master.com',
            'password' => Hash::make('password'),
        ]);

        // Created 3 Users
        for ($i = 0; $i < 29; $i++) {
            User::create([
                'name' => fake()->name,
                'email' => fake()->email(),
                'password' => Hash::make('password'),
            ]);
        }
    }
}
