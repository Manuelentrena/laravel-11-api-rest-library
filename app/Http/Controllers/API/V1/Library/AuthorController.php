<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Attributes\UnauthorizedResponseAttribute;
use App\Attributes\ValidationErrorResponseAttribute;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdatedAuthorRequest;
use App\Http\Resources\API\V1\AuthorResource;
use App\Models\Author;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Schema;


class AuthorController extends Controller
{
    #[Get(
        path: '/api/v1/library/authors',
        summary: 'Get all authors',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Authors list',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Authors retrieved successfully',
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => "info"
            ]
        )
    )]
    #[UnauthorizedResponseAttribute()]
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            AuthorResource::collection(Author::query()->filter()->paginate())->resource,
            'Authors retrieved successfully',
        );
    }

    #[Post(
        path: '/api/v1/library/authors',
        summary: 'Create a new author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'name',
                description: 'Author name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Author created',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author created successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.']
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author created successfully',
            Response::HTTP_CREATED,
        );
    }

    #[Get(
        path: '/api/v1/library/authors/{author}',
        summary: 'Get an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author details',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author retrieved successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Author not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Author] 100',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function show(Author $author): JsonResponse
    {
        return ApiResponseService::success(
            new AuthorResource($author),
            'Author retrieved successfully',
        );
    }

    #[Put(
        path: '/api/v1/library/authors/{author}',
        summary: 'Update an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'name',
                description: 'Author name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author updated',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author updated successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling Updated!',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Author not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Author] 100',
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.'],
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function update(UpdatedAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author updated successfully',
        );
    }

    #[Delete(
        path: '/api/v1/library/authors/{author}',
        summary: 'Delete an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author deleted',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author deleted successfully',
                'data' => null,
                "pagination" => null
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Author not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Author] 100',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CONFLICT,
        description: 'Author with related books',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'Author has one or more books related',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function destroy(Author $author): JsonResponse
    {
        if (!$author->books->isEmpty()) {
            return ApiResponseService::error(
                'Author has one or more books related',
                Response::HTTP_CONFLICT,
            );
        }

        $author->delete();

        return ApiResponseService::success(
            null,
            'Author deleted successfully',
        );
    }
}
