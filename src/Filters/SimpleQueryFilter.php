<?php

namespace Mnabialek\LaravelEloquentFilter\Filters;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;
use Mnabialek\LaravelEloquentFilter\Parsers\SimpleQueryParser;

abstract class SimpleQueryFilter extends QueryFilter
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
