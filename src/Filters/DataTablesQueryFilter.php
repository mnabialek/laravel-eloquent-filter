<?php

namespace Mnabialek\LaravelEloquentFilter\Filters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Parsers\DataTablesQueryParser;

abstract class DataTablesQueryFilter extends QueryFilter
{
    /**
     * DataTablesQueryFilter constructor.
     *
     * @param DataTablesQueryParser $parser
     * @param Collection $collection
     * @param Container $app
     */
    public function __construct(
        DataTablesQueryParser $parser,
        Collection $collection,
        Container $app
    ) {
        parent::__construct($parser, $collection, $app);
    }
}
