<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookStockRequest;
use App\Http\Resources\API\V1\Book\BookCollection;
use App\Http\Resources\API\V1\Book\BookResource;
use App\Models\Book;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponseService::success(
            new BookCollection(Book::query()->with('author', 'genre')->filter()->paginate()),
            "Books retrieved succesfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $data = $request->validated();
        $book = Book::create($request->validated());

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book created successfully',
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book retrieved successfully',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book updated successfully',
        );
    }

    public function updateStock(UpdateBookStockRequest $request, Book $book): JsonResponse
    {

        $newStock = data_get($request->validated(), 'stock', 0);
        $book->update(['stock' => $newStock]);

        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Stock updated successfully',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {


        $book->delete();

        return ApiResponseService::success(
            null,
            'Book deleted successfully',
        );
    }
}
