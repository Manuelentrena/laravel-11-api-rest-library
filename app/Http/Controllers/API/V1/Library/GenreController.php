<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Resources\API\V1\Genre\GenreCollection;
use App\Http\Resources\API\V1\Genre\GenreResource;
use App\Models\Genre;
use App\Services\API\V1\ApiResponseService;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponseService::success(
            new GenreCollection(Genre::query()->filter()->paginate()),
            "Genre retrieved succesfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request)
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
    public function show(Genre $genre)
    {
        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre retrieved successfully',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
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
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return ApiResponseService::success(
            null,
            'Genre deleted successfully',
        );
    }
}
