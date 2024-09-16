<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => new AuthorResource($this->whenLoaded('author')),
            'genre' => new GenreResource($this->whenLoaded('genre')),
            'title' => $this->title,
            'isbn' => $this->isbn,
            'pages' => $this->pages,
            'stock' => $this->stock,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
