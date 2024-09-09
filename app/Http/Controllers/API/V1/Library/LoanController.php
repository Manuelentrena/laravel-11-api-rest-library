<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Loan\StoreLoanRequest;
use App\Http\Resources\API\V1\Loan\LoanCollection;
use App\Http\Resources\API\V1\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            new LoanCollection(Loan::query()->with('book')->filter()->paginate()),
            "Loan retrieved succesfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        if (!Book::find($request->book_id)->stock) {
            return ApiResponseService::error(
                'The book is out of stock',
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $loan = Loan::create([
            'book_id' => $request->book_id,
            'loaned_at' => now(),
            'overdue_at' => now()->addDays(config('library.days_of_loans')),
            'returned_at' => null,
            'is_returned' => false,
        ]);

        $loan->book->update([
            'stock' => $loan->book->stock - config('library.less_stock'),
        ]);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan created successfully',
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan): JsonResponse
    {
        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan retrieved successfully',
        );
    }


    public function returnLoan(Loan $loan): JsonResponse
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

        $loan->update([
            'is_returned' => true,
            'returned_at' => now(),
        ]);

        $loan->book->update([
            'stock' => $loan->book->stock + config('library.more_stock'),
        ]);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan returned successfully',
        );
    }
}
