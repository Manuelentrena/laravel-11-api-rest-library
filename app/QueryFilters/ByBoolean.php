<?php

namespace App\QueryFilters;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByBoolean
{
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }
    public function handle(Builder $query, Closure $next)
    {
        $value = request($this->field);

        if ($value === 'true' || $value === 'false') {
            $booleanValue = $value === 'true';
            $query->where($this->field, '=', $booleanValue);
        }

        return $next($query);
    }
}
