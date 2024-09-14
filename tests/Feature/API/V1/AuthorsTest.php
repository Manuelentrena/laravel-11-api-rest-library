<?php

namespace Tests\Feature\API\V1;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
