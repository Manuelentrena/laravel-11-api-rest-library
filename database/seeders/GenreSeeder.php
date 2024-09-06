<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        // Remove the data
        Genre::truncate();

        // Created 3 Genre
        for ($i = 0; $i < 4; $i++) {
            Genre::create([
                'name' => fake()->name,
            ]);
        }
    }
}
