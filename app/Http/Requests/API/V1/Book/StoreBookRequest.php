<?php

namespace App\Http\Requests\API\V1\Book;

use App\Rules\API\V1\Isbn13;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "author_id" => "required|exists:authors,id",
            "genre_id" => "required|exists:genres,id",
            "title" => "required|string|max:100|unique:books,title",
            "isbn" => ["required", "numeric", "unique:books,isbn", "min:13", new Isbn13()],
            "pages" => "required|integer|min:1",
            "stock" => "required|integer|min:1",
            "published_at" => "required|date",
        ];
    }
}
