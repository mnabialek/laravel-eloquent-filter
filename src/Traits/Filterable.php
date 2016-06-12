<?php

namespace Mnabialek\LaravelEloquentFilter\Traits;

use Illuminate\Database\Eloquent\Builder;
use Mnabialek\LaravelEloquentFilter\Contracts\QueryFilter;

trait Filterable
{
    /**
     * Apply all filters to given query
     *
     * @param Builder $query
     * @param QueryFilter $filter
     *
     * @return Builder
     */
    public function scopeFiltered($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
