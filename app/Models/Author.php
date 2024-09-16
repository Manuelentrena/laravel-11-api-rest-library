<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Filterable;

class Author extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = ["name"];

    protected $filters = [
        'name' => \App\QueryFilters\ByTextCaseInsensitive::class,
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
