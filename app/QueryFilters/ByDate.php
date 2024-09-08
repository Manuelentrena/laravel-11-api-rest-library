<?php

namespace App\QueryFilters;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByDate
{
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }
    public function handle(Builder $query, Closure $next)
    {
        $date = Carbon::parse(request($this->field))->format('Y-m-d');

        $query->when($date, function ($query) use ($date) {
            $query->whereDate($this->field, '=', $date);
        });

        return $next($query);
    }
}
