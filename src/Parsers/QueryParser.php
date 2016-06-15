<?php

namespace Mnabialek\LaravelEloquentFilter\Parsers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class QueryParser
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * Ignore filters with empty values
     *
     * @var bool
     */
    protected $ignoreEmptyFilters = false;

    /**
     * Filters that should be ignored
     *
     * @var array
     */
    protected $ignoredFilters = [];

    /**
     * QueryParser constructor.
     *
     * @param Request $request
     * @param Collection $collection
     */
    public function __construct(Request $request, Collection $collection)
    {
        $this->request = $request;
        $this->collection = $collection;
    }

    /**
     * Add filter to ignored
     *
     * @param $field
     */
    protected function addIgnoredFilter($field)
    {
        $this->ignoredFilters[] = $field;
    }

    /**
     * Get ignored filters
     *
     * @return array
     */
    protected function getIgnoredFilters()
    {
        return $this->ignoredFilters;
    }

    /**
     * Get default filter operator
     *
     * @return string
     */
    protected function getDefaultOperator()
    {
        return '=';
    }
}
