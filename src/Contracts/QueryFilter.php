<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface QueryFilter
{
    /**
     * Apply all filters to given query
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function apply($query);

    /**
     * Apply condition filters only to given query
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function applyFilters($query);

    /**
     * Apply sorting filters only to given query
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function applySorts($query);
}
