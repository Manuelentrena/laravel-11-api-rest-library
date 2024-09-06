<?php

namespace App\Http\Resources\API\V1\Author;

use App\Http\Resources\API\V1\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
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
