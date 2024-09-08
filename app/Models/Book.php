<?php

namespace App\Models;


use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        "author_id",
        "genre_id",
        "title",
        "isbn",
        "pages",
        "stock",
        "published_at",
    ];

    protected $filters = [
        'title' => \App\QueryFilters\ByTextCaseInsensitive::class,
        'isbn' => \App\QueryFilters\ByIntegerEqual::class,
        'pages' => \App\QueryFilters\ByIntegerEqual::class,
        'stock' => \App\QueryFilters\ByIntegerEqual::class,
        'published_at' => \App\QueryFilters\ByDate::class,
    ];

    protected function casts(): array
    {
        return [
            "published_at" => "date:Y-m-d",
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
