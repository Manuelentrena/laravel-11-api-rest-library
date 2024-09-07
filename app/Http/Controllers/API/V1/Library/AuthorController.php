<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdatedAuthorRequest;
use App\Http\Resources\API\V1\Author\AuthorCollection;
use App\Http\Resources\API\V1\Author\AuthorResource;
use App\Models\Author;
use App\QueryFilters\ByName;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{

    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            new AuthorCollection(Author::query()->filter()->paginate()),
            "Authors retrieved succesfully"
        );
    }

    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author created successfully',
            Response::HTTP_CREATED,
        );
    }

    public function show(Author $author): JsonResponse
    {
        return ApiResponseService::success(
            new AuthorResource($author),
            'Author retrieved successfully',
        );
    }

    public function update(UpdatedAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author updated successfully',
        );
    }

    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return ApiResponseService::success(
            null,
            'Author deleted successfully',
        );
    }
}
