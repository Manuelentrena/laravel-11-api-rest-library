<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Attributes\UnauthorizedResponseAttribute;
use App\Attributes\ValidationErrorResponseAttribute;
use App\Http\Actions\API\V1\Loan\LoanActions;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Loan\StoreLoanRequest;
use App\Http\Resources\API\V1\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Schema;

class LoanController extends Controller
{
    #[Get(
        path: '/api/v1/library/loans',
        summary: 'Get all loans',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Loans'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Loans list',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Loan retrieved succesfully',
                'data' => [
                    [
                        'id' => 1,
                        'book_id' => 1,
                        'loaned_at' => '2023-10-10T10:00:00Z',
                        'due_date' => '2023-10-17T10:00:00Z',
                        'returned_at' => null,
                        'returned' => false,
                        'created_at' => '2023-10-10T10:00:00Z',
                        'book' => [
                            'id' => 1,
                            'author_id' => 1,
                            'genre_id' => 1,
                            'title' => 'Harry Potter and the Philosopher\'s Stone',
                            'isbn' => '9780590353427',
                            'pages' => 223,
                            'stock' => 5,
                            'published_at' => '1997-06-26',
                            'created_at' => '2023-10-10T10:00:00Z',
                        ],
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
            LoanResource::collection(Loan::query()->with('book', 'book.author', 'book.genre')->filter()->paginate())->resource,
            "Loan retrieved succesfully"
        );
    }

    #[Post(
        path: '/api/v1/library/loans',
        summary: 'Create a new loan',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Loans'],
        parameters: [
            new Parameter(
                name: 'book_id',
                description: 'Book ID',
                in: 'query',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Loan created',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Loan created successfully',
                'data' => [
                    'id' => 1,
                    'book_id' => 1,
                    'loaned_at' => '2023-10-10T10:00:00Z',
                    'due_date' => '2023-10-17T10:00:00Z',
                    'returned_at' => null,
                    'returned' => false,
                    'created_at' => '2023-10-10T10:00:00Z',
                    'book' => [
                        'id' => 1,
                        'author' => [
                            'id' => 1,
                            'name' => 'J. K. Rowling',
                        ],
                        'genre' => [
                            'id' => 1,
                            'name' => 'Fantasy',
                        ],
                        'title' => 'Harry Potter and the Philosopher\'s Stone',
                        'isbn' => '9780590353427',
                        'pages' => 223,
                        'stock' => 4,
                        'published_at' => '1997-06-26',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CONFLICT,
        description: 'Book without stock',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'The book is out of stock',
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'book_id' => ['The book_id is required'],
        ],
    )]
    #[UnauthorizedResponseAttribute]
    public function store(StoreLoanRequest $request, LoanActions $actions): JsonResponse
    {
        if (!Book::find($request->book_id)->stock) {
            return ApiResponseService::error(
                'The book is out of stock',
                Response::HTTP_CONFLICT,
            );
        }

        $loan = $actions->createLoanAndDecreaseStock($request);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan created successfully',
            Response::HTTP_CREATED,
        );
    }

    #[Get(
        path: '/api/v1/library/loans/{loan}',
        summary: 'Get a loan',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Loans'],
        parameters: [
            new Parameter(
                name: 'loan',
                description: 'Loan ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Loan',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Loan retrieved successfully',
                'data' => [
                    'id' => 1,
                    'book_id' => 1,
                    'loaned_at' => '2023-10-10T10:00:00Z',
                    'due_date' => '2023-10-17T10:00:00Z',
                    'returned_at' => null,
                    'returned' => false,
                    'created_at' => '2023-10-10T10:00:00Z',
                    'book' => [
                        'id' => 1,
                        'title' => 'Harry Potter and the Philosopher\'s Stone',
                        'isbn' => '9780590353427',
                        'pages' => 223,
                        'stock' => 4,
                        'published_at' => '1997-06-26',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Loan not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Loan] 100',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function show(Loan $loan): JsonResponse
    {
        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan retrieved successfully',
        );
    }

    #[Patch(
        path: '/api/v1/library/loans/{loan}/return',
        summary: 'Return a loan',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Loans'],
        parameters: [
            new Parameter(
                name: 'loan',
                description: 'Loan ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Loan returned',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Loan returned successfully',
                'data' => [
                    'id' => 1,
                    'book_id' => 1,
                    'loaned_at' => '2023-10-10T10:00:00Z',
                    'due_date' => '2023-10-17T10:00:00Z',
                    'returned_at' => '2023-10-17T10:00:00Z',
                    'returned' => true,
                    'created_at' => '2023-10-10T10:00:00Z',
                    'book' => [
                        'id' => 1,
                        'title' => 'Harry Potter and the Philosopher\'s Stone',
                        'isbn' => '9780590353427',
                        'pages' => 223,
                        'stock' => 5,
                        'published_at' => '1997-06-26',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ],
                "pagination" => null,
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Loan already returned',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'The book was already returned',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Not Owner',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'You are not the owner of the loan',
            ]
        )
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Loan not found',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'error',
                'message' => 'No query results for model [App\\Models\\Loan] 100',
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function returnLoan(Loan $loan, LoanActions $actions): JsonResponse
    {
        if ($loan->is_returned) {
            return ApiResponseService::error(
                'The book was already returned',
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        if (!$loan->isOwner()) {
            return ApiResponseService::error(
                'You are not the owner of the loan',
                Response::HTTP_FORBIDDEN,
            );
        }

        $loan = $actions->updateLoanAndAddStock($loan);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan returned successfully',
        );
    }
}
