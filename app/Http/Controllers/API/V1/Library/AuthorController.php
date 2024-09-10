<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdatedAuthorRequest;
use App\Http\Resources\API\V1\AuthorResource;
use App\Models\Author;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{

    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            AuthorResource::collection(Author::query()->filter()->paginate())->resource,
            'Authors retrieved successfully',
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
        if ($author->books) {
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
