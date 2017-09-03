<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

use Illuminate\Support\Collection;

interface InputParser
{
    /**
     * Get filters.
     *
     * @return Collection
     */
    public function getFilters();

    /**
     * Get parameters for sorting.
     *
     * @return Collection
     */
    public function getSorts();
}
