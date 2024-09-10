<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Attributes\UnauthorizedResponseAttribute;
use App\Attributes\ValidationErrorResponseAttribute;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookStockRequest;
use App\Http\Resources\API\V1\BookResource;
use App\Models\Book;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Schema;

class BookController extends Controller
{
    #[Get(
        path: '/api/v1/library/books',
        summary: 'Get all books',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Books list',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Books retrieved succesfully',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'Harry Potter and the Philosopher\'s Stone',
                        'isbn' => '978-3-16-148410-0',
                        'author' => [
                            'id' => 1,
                            'name' => 'J. K. Rowling',
                        ],
                        'genre' => [
                            'id' => 1,
                            'name' => 'Fantasy',
                        ],
                        'stock' => 10,
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => "info",
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            BookResource::collection(Book::query()->with('author', 'genre')->filter()->paginate())->resource,
            "Books retrieved succesfully"
        );
    }

    #[Post(
        path: '/api/v1/library/books',
        summary: 'Create a new book',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
        parameters: [
            new Parameter(
                name: 'title',
                description: 'Book title',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'isbn',
                description: 'Book ISBN',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'author_id',
                description: 'Author ID',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'genre_id',
                description: 'Genre ID',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'stock',
                description: 'Book stock',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'pages',
                description: 'Book pages',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'published_at',
                description: 'Book published date',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string', format: 'date'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Book created',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Book created successfully',
                'data' => [
                    'id' => 1,
                    'title' => 'Harry Potter and the Philosopher\'s Stone',
                    'isbn' => '978-3-16-148410-0',
                    'author' => [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                    ],
                    'genre' => [
                        'id' => 1,
                        'name' => 'Fantasy',
                    ],
                    'stock' => 10,
                    'pages' => 300,
                    'published_at' => '2023-10-10T10:00:00Z',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'author_id' => ['The author_id field is required.'],
            'genre_id' => ['The genre_id field is required.'],
            'title' => ['The title field is required.'],
            'isbn' => ['The isbn field is required.'],
            'stock' => ['The stock field is required.'],
            'pages' => ['The pages field is required.'],
            'published_at' => ['The published_at field is required.'],
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book created successfully',
            Response::HTTP_CREATED,
        );
    }


    #[Get(
        path: '/api/v1/library/books/{book}',
        summary: 'Get a book',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
        parameters: [
            new Parameter(
                name: 'book',
                description: 'Book ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Book details',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Book retrieved successfully',
                'data' => [
                    'id' => 1,
                    'title' => 'Harry Potter and the Philosopher\'s Stone',
                    'isbn' => '978-3-16-148410-0',
                    'author' => [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                    ],
                    'genre' => [
                        'id' => 1,
                        'name' => 'Fantasy',
                    ],
                    'stock' => 10,
                    'pages' => 300,
                    'published_at' => '2023-10-10T10:00:00Z',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Book] 100',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function show(Book $book): JsonResponse
    {
        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book retrieved successfully',
        );
    }

    #[Put(
        path: '/api/v1/library/books/{book}',
        summary: 'Update a book',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
        parameters: [
            new Parameter(
                name: 'book',
                description: 'Book ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'title',
                description: 'Book title',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'isbn',
                description: 'Book ISBN',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'author_id',
                description: 'Author ID',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'genre_id',
                description: 'Genre ID',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'stock',
                description: 'Book stock',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'pages',
                description: 'Book pages',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'published_at',
                description: 'Book published date',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string', format: 'date'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Book updated',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Book updated successfully',
                'data' => [
                    'id' => 1,
                    'title' => 'Harry Potter and the Philosopher\'s Stone',
                    'isbn' => '978-3-16-148410-0',
                    'author' => [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                    ],
                    'genre' => [
                        'id' => 1,
                        'name' => 'Fantasy',
                    ],
                    'stock' => 10,
                    'pages' => 300,
                    'published_at' => '2023-10-10T10:00:00Z',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Book] 100',
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'author_id' => ['The author_id field is required.'],
            'genre_id' => ['The genre_id field is required.'],
            'isbn' => ['The isbn field is required.'],
            'title' => ['The title field is required.'],
            'stock' => ['The stock field is required.'],
            'pages' => ['The pages field is required.'],
            'published_at' => ['The published_at field is required.'],
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book updated successfully',
        );
    }

    #[Patch(
        path: '/api/v1/library/books/{book}/stock',
        summary: 'Update a book stock',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
        parameters: [
            new Parameter(
                name: 'book',
                description: 'Book ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'stock',
                description: 'Book stock',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Stock updated',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Stock updated successfully',
                'data' => [
                    'id' => 1,
                    'title' => 'Harry Potter and the Philosopher\'s Stone',
                    'author' => [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                    ],
                    'genre' => [
                        'id' => 1,
                        'name' => 'Fantasy',
                    ],
                    'stock' => 10,
                    'pages' => 300,
                    'published_at' => '2023-10-10T10:00:00Z',
                    'created_at' => '2023-10-10T10:00:00Z',
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Book] 100',
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'stock' => ['The stock field is required.'],
        ]
    )]
    #[UnauthorizedResponseAttribute]
    public function updateStock(UpdateBookStockRequest $request, Book $book): JsonResponse
    {

        $newStock = data_get($request->validated(), 'stock', 0);
        $book->update(['stock' => $newStock]);

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Stock updated successfully',
        );
    }

    #[Delete(
        path: '/api/v1/library/books/{book}',
        summary: 'Delete a book',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Books'],
        parameters: [
            new Parameter(
                name: 'book',
                description: 'Book ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Book deleted',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Book deleted successfully',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Book] 100',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CONFLICT,
        description: 'Book with loans related',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'Book has one or more loans related',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function destroy(Book $book): JsonResponse
    {
        if ($book->loans) {
            return ApiResponseService::error(
                'Book has one or more loans related',
                Response::HTTP_CONFLICT,
            );
        }

        $book->delete();

        return ApiResponseService::success(
            null,
            'Book deleted successfully',
        );
    }
}
