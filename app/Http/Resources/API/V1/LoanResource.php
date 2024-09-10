<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book' => new BookResource($this->whenLoaded('book')),
            'loaned_at' => $this->loaned_at,
            'returned_at' => $this->returned_at,
            'overdue_at' => $this->overdue_at,
            'is_returned' => $this->is_returned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
