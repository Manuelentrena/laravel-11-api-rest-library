<?php

namespace App\Models;

use App\Models\Scopes\Userscope;
use App\Traits\Filterable;
use App\Traits\HasUserId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory, Filterable, HasUserId;

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new Userscope());
    }

    protected $fillable = [
        "user_id",
        "book_id",
        "loaned_at",
        "returned_at",
        "overdue_at",
        "is_returned",
    ];

    protected $filters = [
        'is_returned' => \App\QueryFilters\ByBoolean::class,
        'loaned_at' => \App\QueryFilters\ByDate::class,
        'overdure_at' => \App\QueryFilters\ByDate::class,
        'returned_at' => \App\QueryFilters\ByDate::class,
    ];

    protected function casts(): array
    {
        return [
            "loaned_at" => "datetime",
            "returned_at" => "datetime",
            "overdue_at" => "datetime",
            "is_returned" => "boolean",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isOwner(): bool
    {
        return $this->user_id === auth()->id();
    }
}
