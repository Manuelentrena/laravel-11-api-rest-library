<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Actions\API\V1\Loan\LoanActions;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Loan\StoreLoanRequest;
use App\Http\Resources\API\V1\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            LoanResource::collection(Loan::query()->with('book', 'book.author', 'book.genre')->filter()->paginate())->resource,
            "Loan retrieved succesfully"
        );
    }

    public function store(StoreLoanRequest $request, LoanActions $actions): JsonResponse
    {
        if (!Book::find($request->book_id)->stock) {
            return ApiResponseService::error(
                'The book is out of stock',
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $loan = $actions->createLoanAndDecreaseStock($request);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan created successfully',
            Response::HTTP_CREATED,
        );
    }

    public function show(Loan $loan): JsonResponse
    {
        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan retrieved successfully',
        );
    }


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
