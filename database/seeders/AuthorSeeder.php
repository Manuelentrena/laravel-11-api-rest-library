<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        // Remove the data
        Author::truncate();

        // Created 3 Authors
        for ($i = 0; $i < 15; $i++) {
            Author::create([
                'name' => fake()->name,
            ]);
        }
    }
}
