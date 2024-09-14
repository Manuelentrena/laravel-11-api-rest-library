<?php

namespace Tests\Feature\API\V1;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('api.v1')]
#[Group('api.v1.authors')]
class AuthorsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthenticated_user_cannot_access(): void
    {
        $this->getJson(route('v1.authors.index'))->assertUnauthorized();
    }

    #[Test]
    public function authors_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        Author::factory(10)->create();

        $response = $this->
            withToken($token)
            ->getJson(route('v1.authors.index'))
            ->assertJson([
                'message' => 'Authors retrieved successfully',
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
    public function an_author_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $author = Author::factory()->create();

        $response = $this->
            withToken($token)
            ->getJson(route('v1.authors.show', $author))
            ->assertJson([
                'message' => 'Author retrieved successfully',
                'data' => [
                    'id' => $author->id,
                    'name' => $author->name,
                    'created_at' => $author->created_at->format('Y-m-d H:i:s'),
                ],
                "pagination" => null
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());

    }

    #[Test]
    public function an_author_can_be_created(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson(route('v1.authors.store'), [
                "name" => "author",
            ])
            ->assertJson([
                'message' => 'Author created successfully',
                "pagination" => null,
            ])
            ->assertCreated();

        $this->assertCount(4, $response->json());
        $response->assertJson([
            'data' => [
                'id' => (int) $response->json('data.id'),
                'name' => $response->json('data.name'),
                'created_at' => $response->json('data.created_at'),
            ],
        ]);

        $this->assertDatabaseHas('authors', [
            'id' => (int) $response->json('data.id'),
            'name' => $response->json('data.name'),
            'created_at' => $response->json('data.created_at'),
        ]);
    }

    #[Test]
    public function an_author_can_be_updated(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $author = Author::factory()->create();

        $response = $this
            ->withToken($token)
            ->putJson(route('v1.authors.update', $author), [
                "name" => "author_new_name",
            ])
            ->assertJson([
                'message' => 'Author updated successfully',
                "pagination" => null,
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());
        $response->assertJson([
            'data' => [
                'id' => (int) $response->json('data.id'),
                'name' => "author_new_name",
                'created_at' => $response->json('data.created_at'),
            ],
        ]);

        $this->assertDatabaseHas('authors', [
            'id' => (int) $author->id,
            'name' => "author_new_name",
            'created_at' => $author->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    #[Test]
    public function an_author_can_be_deleted(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $author = Author::factory()->create();

        $response = $this
            ->withToken($token)
            ->deleteJson(route('v1.authors.destroy', $author), [
                "name" => "author_new_name",
            ])
            ->assertJson([
                'message' => 'Author deleted successfully',
                'data' => null,
                'pagination' => null,
            ])
            ->assertOk();

        $this->assertCount(4, $response->json());

        $this->assertDatabaseMissing('authors', [
            'id' => (int) $author->id,
        ]);
    }

}
