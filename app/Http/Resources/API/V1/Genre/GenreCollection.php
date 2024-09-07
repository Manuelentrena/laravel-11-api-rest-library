<?php

namespace App\Http\Resources\API\V1\Genre;

use App\Http\Resources\API\V1\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pagination = new PaginationResource($this);
        return [
            'results' => $this->collection,
            'info' => $pagination,
        ];
    }
}
