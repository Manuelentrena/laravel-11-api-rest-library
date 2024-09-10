<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Resources\API\V1\GenreResource;
use App\Models\Genre;
use App\Services\API\V1\ApiResponseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            GenreResource::collection(Genre::query()->filter()->paginate())->resource,
            "Genre retrieved succesfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $genre = Genre::create($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre created successfully',
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): JsonResponse
    {
        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre retrieved successfully',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $genre->update($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre updated successfully',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
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
