<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Attributes\UnauthorizedResponseAttribute;
use App\Attributes\ValidationErrorResponseAttribute;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Resources\API\V1\GenreResource;
use App\Models\Genre;
use App\Services\API\V1\ApiResponseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Schema;

class GenreController extends Controller
{
    #[Get(
        path: '/api/v1/library/genres',
        summary: 'Get all genres',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Genres'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Genres list',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Genre retrieved succesfully',
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Fantasía',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => "info",
            ]
        )
    )]
    #[UnauthorizedResponseAttribute()]
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            GenreResource::collection(Genre::query()->filter()->paginate())->resource,
            "Genre retrieved succesfully"
        );
    }

    #[Post(
        path: '/api/v1/library/genres',
        summary: 'Create a new genre',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Genres'],
        parameters: [
            new Parameter(
                name: 'name',
                description: 'Genre name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Genre created',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Genre created successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'Fantasía',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.']
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $genre = Genre::create($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre created successfully',
            Response::HTTP_CREATED,
        );
    }

    #[Get(
        path: '/api/v1/library/genres/{genre}',
        summary: 'Get a genre',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Genres'],
        parameters: [
            new Parameter(
                name: 'genre',
                description: 'Genre ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Genre details',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Genre retrieved successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'Fantasía',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Genre not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Genre] 100',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function show(Genre $genre): JsonResponse
    {
        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre retrieved successfully',
        );
    }

    #[Put(
        path: '/api/v1/library/genres/{genre}',
        summary: 'Update a genre',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Genres'],
        parameters: [
            new Parameter(
                name: 'genre',
                description: 'Genre ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'name',
                description: 'Genre name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Genre updated',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Genre updated successfully',
                'data' => [
                    'id' => 1,
                    'name' => 'Fantasy',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                'pagination' => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Genre not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Genre] 100',
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.']
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $genre->update($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre updated successfully',
        );
    }

    #[Delete(
        path: '/api/v1/library/genres/{genre}',
        summary: 'Delete a genre',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Genres'],
        parameters: [
            new Parameter(
                name: 'genre',
                description: 'Genre ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Genre deleted',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Genre deleted successfull',
                'data' => null,
                'pagination' => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Genre not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Genre] 100',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CONFLICT,
        description: 'Genre with related books',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'Genre has one or more books related',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function destroy(Genre $genre): JsonResponse
    {
        if ($genre->books) {
            return ApiResponseService::error(
                'Genre has one or more books related',
                Response::HTTP_CONFLICT,
            );
        }

        $genre->delete();

        return ApiResponseService::success(
            null,
            'Genre deleted successfully',
        );
    }
}
