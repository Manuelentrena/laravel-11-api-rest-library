<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter(Builder $query)
    {
        $filters = $this->getFiltersFromRequest();

        return app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->thenReturn();
    }

    protected function getFiltersFromRequest()
    {
        $classes = [];
        $filters = $this->filters ?? [];

        foreach (request()->query() as $key => $value) {
            if (isset($filters[$key])) {
                $classes[] = $filters[$key];
            }
        }

        return $classes;
    }
}
