<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

use Illuminate\Support\Collection;

interface InputParser
{
    /**
     * Get conditions
     *
     * @return Collection
     */
    public function getConditions();

    /**
     * Get parameters for sorting
     *
     * @return Collection
     */
    public function getSorts();
}
