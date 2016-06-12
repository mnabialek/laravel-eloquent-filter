<?php

namespace Mnabialek\LaravelEloquentFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Contracts\Condition;
use Mnabialek\LaravelEloquentFilter\Contracts\InputParser;
use Mnabialek\LaravelEloquentFilter\Contracts\Sort;

class QueryFilter implements Contracts\QueryFilter
{
    /**
     * @var Collection
     */
    protected $conditions;

    /**
     * @var Collection
     */
    protected $sorts;

    /**
     * @var Collection
     */
    protected $appliedConditions;

    /**
     * @var Collection
     */
    protected $appliedSorts;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * Array of allowed conditions to be used
     *
     * @var array
     */
    protected $simpleConditions = [];

    /**
     * Array of allowed sorting to be used
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
     * QueryFilter constructor.
     *
     * @param InputParser $parser
     * @param Collection $collection
     */
    public function __construct(InputParser $parser, Collection $collection)
    {
        $this->parser = $parser;
        $this->collection = $collection;
        $this->conditions = $parser->getConditions();
        $this->sorts = $parser->getSorts();
        $this->appliedConditions = $collection->make();
        $this->appliedSorts = $collection->make();
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

        $this->conditions->each(function ($condition) {
            /** @var Condition $condition */
            $field = $condition->getField();
            $method = $this->getFilterMethod($field);

            if (method_exists($this, $method)) {
                $this->$method($condition->getValue(),
                    $condition->getOperator());
                $this->appliedConditions->push($field);
            } elseif (in_array($field, $this->simpleConditions)) {
                $this->applySimpleCondition($condition);
                $this->appliedConditions->push($field);
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
     * Get custom filter method name
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
     * Get custom sort method name
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
     * you can use $appliedConditions property to verify whether filter for
     * selected field has been already applied or not
     */
    protected function applyDefaultFilters()
    {
    }

    /**
     * Apply default sorts after applying any other sorts. In this method
     * you can use $appliedSorts property to verify whether sorts for
     * selected field has been already applied or not
     */
    protected function applyDefaultSorts()
    {
    }

    /**
     * Apply simple condition
     *
     * @param Condition $condition
     */
    protected function applySimpleCondition(Condition $condition)
    {
        $value = $condition->getValue();
        if (is_array($value)) {
            $this->query->whereIn($condition->getField(), $value);
        } else {
            $this->query->where($condition->getField(),
                $condition->getOperator(), $value);
        }
    }

    /**
     * Apply simple sort
     *
     * @param Sort $sort
     */
    protected function applySimpleSort(Sort $sort)
    {
        $this->query->orderBy($sort->getField(), $sort->getOrder());
    }
}
