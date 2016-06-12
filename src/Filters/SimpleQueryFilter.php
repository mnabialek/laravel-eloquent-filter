<?php

namespace Mnabialek\LaravelEloquentFilter\Filters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Parsers\SimpleQueryParser;

class SimpleQueryFilter extends QueryFilter
{
    /**
     * SimpleQueryFilter constructor.
     *
     * @param SimpleQueryParser $parser
     * @param Collection $collection
     * @param Container $app
     */
    public function __construct(
        SimpleQueryParser $parser,
        Collection $collection,
        Container $app
    ) {
        parent::__construct($parser, $collection, $app);
    }
}
