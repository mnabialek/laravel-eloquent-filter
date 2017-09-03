<?php

namespace Mnabialek\LaravelEloquentFilter\Filters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Contracts\Filter;
use Mnabialek\LaravelEloquentFilter\Contracts\InputParser;
use Mnabialek\LaravelEloquentFilter\Contracts\QueryFilter as QueryFilterContract;
use Mnabialek\LaravelEloquentFilter\Contracts\Sort;

abstract class QueryFilter implements QueryFilterContract
{
    /**
     * @var Collection
     */
    protected $filters;

    /**
     * @var Collection
     */
    protected $sorts;

    /**
     * @var Collection
     */
    protected $appliedFilters;

    /**
     * @var Collection
     */
    protected $appliedSorts;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * Array of allowed filters to be used.
     *
     * @var array
     */
    protected $simpleFilters = [];

    /**
     * Array of allowed sorting to be used.
     *
     * @var array
     */
    protected $simpleSorts = [];

    /**
     * @var InputParser
     */
    protected $parser;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Container
     */
    protected $app;

    /**
     * QueryFilter constructor.
     *
     * @param InputParser $parser
     * @param Collection $collection
     * @param Container $app
     */
    public function __construct(
        InputParser $parser,
        Collection $collection,
        Container $app
    ) {
        $this->parser = $parser;
        $this->collection = $collection;
        $this->filters = $parser->getFilters();
        $this->sorts = $parser->getSorts();
        $this->appliedFilters = $collection->make();
        $this->appliedSorts = $collection->make();
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($query)
    {
        $query = $this->applyFilters($query);

        return $this->applySorts($query);
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilters($query)
    {
        $this->query = $query;

        $this->filters->each(function ($filter) {
            /** @var Filter $filter */
            $field = $filter->getField();
            $method = $this->getFilterMethod($field);

            if (method_exists($this, $method)) {
                $this->$method($filter->getValue(),
                    $filter->getOperator());
                $this->appliedFilters->push($field);
            } elseif (in_array($field, $this->simpleFilters)) {
                $this->applySimpleFilter($filter);
                $this->appliedFilters->push($field);
            }
        });

        $this->applyDefaultFilters();

        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function applySorts($query)
    {
        $this->query = $query;

        $this->sorts->each(function ($sort) {
            /** @var Sort $sort */
            $field = $sort->getField();
            $method = $this->getSortMethod($field);

            if (method_exists($this, $method)) {
                $this->$method($sort->getOrder());
                $this->appliedSorts->push($field);
            } elseif (in_array($field, $this->simpleSorts)) {
                $this->applySimpleSort($sort);
                $this->appliedSorts->push($field);
            }
        });

        $this->applyDefaultSorts();

        return $this->query;
    }

    /**
     * Get custom filter method name.
     *
     * @param string $field
     *
     * @return string
     */
    protected function getFilterMethod($field)
    {
        return 'apply' . studly_case(str_replace('.', ' ', $field));
    }

    /**
     * Get custom sort method name.
     *
     * @param string $field
     *
     * @return string
     */
    protected function getSortMethod($field)
    {
        return 'applySort' . studly_case(str_replace('.', ' ', $field));
    }

    /**
     * Apply default filters after applying any other filters. In this method
     * you can use $appliedFilters property to verify whether filter for
     * selected field has been already applied or not.
     */
    protected function applyDefaultFilters()
    {
    }

    /**
     * Apply default sorts after applying any other sorts. In this method
     * you can use $appliedSorts property to verify whether sorts for
     * selected field has been already applied or not.
     */
    protected function applyDefaultSorts()
    {
    }

    /**
     * Apply simple filter.
     *
     * @param Filter $filter
     */
    protected function applySimpleFilter(Filter $filter)
    {
        $value = (array) $filter->getValue();
        if (count($value) > 1) {
            $this->query->whereIn($filter->getField(), $value);
        } else {
            $this->query->where($filter->getField(),
                $filter->getOperator(), $value[0]);
        }
    }

    /**
     * Apply simple sort.
     *
     * @param Sort $sort
     */
    protected function applySimpleSort(Sort $sort)
    {
        $this->query->orderBy($sort->getField(), $sort->getOrder());
    }
}
