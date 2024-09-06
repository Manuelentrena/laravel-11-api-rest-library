<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove the data
        Book::truncate();
        // Created books
        for ($i = 0; $i < 100; $i++) {
            Book::create([
                'author_id' => fake()->numberBetween(1, 15),
                'genre_id' => fake()->numberBetween(1, 4),
                'title' => fake()->jobTitle(),
                'isbn' => fake()->isbn13(),
                'pages' => fake()->randomNumber(3, true),
                'stock' => fake()->randomNumber(1, true),
                'published_at' => fake()->dateTimeBetween(),
            ]);
        }
    }
}
