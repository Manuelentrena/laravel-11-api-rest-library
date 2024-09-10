<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Attributes\UnauthorizedResponseAttribute;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends AuthController
{
    #[Post(
        path: '/api/v1/auth/logout',
        summary: 'Logout a user',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Auth'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Successful logout',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Successfully logged out',
                'data' => null,
                'pagination' => null,
            ]
        )
    )]
    #[UnauthorizedResponseAttribute()]
    public function __invoke(): JsonResponse
    {
        return $this->authService->logout();
    }
}
