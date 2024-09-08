<?php

namespace App\Http\Resources\API\V1\Book;

use App\Http\Resources\API\V1\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        $pagination = new PaginationResource($this);
        return [
            'results' => $this->collection,
            'info' => $pagination,
        ];
    }
}
