<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByName
{
    public function handle(Builder $query, Closure $next)
    {
        $query->when(request('name'), function ($query) {
            $query->where('name', 'LIKE', '%' . request('name') . '%');
        });

        return $next($query);
    }
}
