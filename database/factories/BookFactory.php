<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => fake()->optional()->numberBetween(1, 15),
            'genre_id' => fake()->optional()->numberBetween(1, 4),
            'title' => fake()->jobTitle(),
            'isbn' => fake()->isbn13(),
            'pages' => fake()->randomNumber(3, true),
            'stock' => fake()->randomNumber(1, true),
            'published_at' => fake()->dateTimeBetween(),
        ];
    }
}
