<?php

namespace Tests\Feature\API\V1;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('api.v1')]
#[Group('api.v1.genres')]
class GenresTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function anUnauthenticatedGenreCannotAccess(): void
    {
        $this->getJson(route('v1.genres.index'))->assertUnauthorized();
    }

    #[Test]
    public function genresCanBeRetrieved(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        Genre::factory(10)->create();

        $response = $this->
            withToken($token)
            ->getJson(route('v1.genres.index'))
            ->assertJson([
                'message' => 'Genres retrieved successfully',
            ])
            ->assertOk();

        $this->assertCount(10, $response->json('data'));
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at'
                ]
            ]
        ]);
    }

    #[Test]
    public function anGenreCanBeRetrieved(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $genre = Genre::factory()->create();

        $response = $this->
            withToken($token)
            ->getJson(route('v1.genres.show', $genre))
            ->assertJson([
                'message' => 'Genre retrieved successfully',
                'data' => [
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'created_at' => $genre->created_at->format('Y-m-d H:i:s'),
                ],
                "pagination" => null
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());
    }

    #[Test]
    public function anGenreCanBeCreated(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson(route('v1.genres.store'), [
                "name" => "genre_testing",
            ])
            ->assertJson([
                'message' => 'Genre created successfully',
                "pagination" => null,
            ])
            ->assertCreated();

        $this->assertCount(4, $response->json());
        $response->assertJson([
            'data' => [
                'id' => (int) $response->json('data.id'),
                'name' => "genre_testing",
                'created_at' => $response->json('data.created_at'),
            ],
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => (int) $response->json('data.id'),
            'name' => "genre_testing",
            'created_at' => $response->json('data.created_at'),
        ]);
    }

    #[Test]
    public function anGenreCanBeUpdated(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $genre = Genre::factory()->create();

        $response = $this
            ->withToken($token)
            ->putJson(route('v1.genres.update', $genre), [
                "name" => "genre_updated",
            ])
            ->assertJson([
                'message' => 'Genre updated successfully',
                "pagination" => null,
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());
        $response->assertJson([
            'data' => [
                'id' => (int) $response->json('data.id'),
                'name' => "genre_updated",
                'created_at' => $response->json('data.created_at'),
            ],
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => (int) $genre->id,
            'name' => "genre_updated",
            'created_at' => $genre->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    #[Test]
    public function anGenreCanBeDeleted(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $genre = Genre::factory()->create();

        $response = $this
            ->withToken($token)
            ->deleteJson(route('v1.genres.destroy', $genre))
            ->assertJson([
                'message' => 'Genre deleted successfully',
                'data' => null,
                'pagination' => null,
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());

        $this->assertDatabaseMissing('genres', [
            'id' => (int) $genre->id,
        ]);
    }

    #[Test]
    public function anGenreCanNotBeDeleted(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $genre = Genre::factory()->create();
        $author = Author::factory()->create();
        Book::factory()->create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
        ]);

        $response = $this
            ->withToken($token)
            ->deleteJson(route('v1.genres.destroy', $genre))
            ->assertJson([
                'status' => 'error',
                'message' => 'Genre has one or more books related',
            ])
            ->assertConflict();

        $this->assertCount(2, $response->json());

        $this->assertDatabaseHas('genres', [
            'id' => (int) $genre->id,
        ]);
    }
}
