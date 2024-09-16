<?php

namespace Tests\Feature\API\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('api.v1')]
#[Group('api.v1.auth')]
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function anUserCanRegister(): void
    {
        $response = $this->postJson(route("v1.auth.register"), [
            "name" => "Cursosdesarrolloweb",
            "email" => "api@cursosdesarrolloweb.es",
            "password" => "password",
            "device_name" => "testing"
        ])->assertOk();

        $this->assertArrayHasKey("token", $response->json("data"));
        $this->assertArrayHasKey("token_type", $response->json("data"));
    }

    #[Test]
    public function anUserCanLogin(): void
    {

        $user = User::factory()->create();

        $response = $this->postJson(route("v1.auth.login"), [
            "email" => $user->email,
            "password" => "password",
            "device_name" => "testing"
        ])->assertOk();

        $this->assertArrayHasKey("token", $response->json("data"));
        $this->assertArrayHasKey("token_type", $response->json("data"));
    }

    #[Test]
    public function anUserCanLogout(): void
    {

        $user = User::factory()->create();

        $token = $user->createToken("test")->plainTextToken;

        $this->
            withToken($token)
            ->postJson(route("v1.auth.logout"))
            ->assertJson([
                "message" => "Successfully logged out",
            ])
            ->assertOk();
        $this->assertEmpty($user->tokens);
    }
}
