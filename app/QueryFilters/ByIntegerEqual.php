<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByIntegerEqual
{
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }
    public function handle(Builder $query, Closure $next)
    {
        $query->when(request($this->field), function ($query) {
            $query->where($this->field, '=', request($this->field));
        });

        return $next($query);
    }
}
