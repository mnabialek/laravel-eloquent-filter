<?php

namespace Mnabialek\LaravelEloquentFilter\Parsers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mnabialek\LaravelEloquentFilter\Contracts\InputParser;
use Mnabialek\LaravelEloquentFilter\Objects\Filter;
use Mnabialek\LaravelEloquentFilter\Objects\Sort;

class SimpleQueryParser implements InputParser
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Name of input that holds sorting order.
     *
     * @var string
     */
    protected $sortName = 'sort';

    /**
     * Separator for sort fields.
     *
     * @var string
     */
    protected $sortFieldsSeparator = ',';

    /**
     * Sign to mark that field should be sorted in descending order.
     *
     * @var string
     */
    protected $sortDescSign = '-';

    /**
     * Ignore filters with empty values.
     *
     * @var bool
     */
    protected $ignoreEmptyFilters = false;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * SimpleQueryParser constructor.
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
     * Get filters.
     *
     * @return Collection
     */
    public function getFilters()
    {
        $input = $this->collection->make(
            $this->request->except($this->sortName));

        $filters = $this->collection->make();

        $input->filter(function ($value) {
            if ($this->ignoreEmptyFilters && is_string($value) &&
                (string) $value == ''
            ) {
                return false;
            }

            return true;
        })->each(function ($value, $field) use ($filters) {
            $filter = new Filter();
            $filter->setField($field);
            $filter->setValue($value);
            $filters->push($filter);
        });

        return $filters;
    }

    /**
     * Get parameters for sorting.
     *
     * @return Collection
     */
    public function getSorts()
    {
        $sortInput = $this->request->input($this->sortName, '');

        $sortFields = $this->collection->make(
            is_array($sortInput) ? $sortInput :
                explode($this->sortFieldsSeparator, $sortInput));

        $sorts = $this->collection->make();

        $sortFields->each(function ($field) use ($sorts) {
            $s = new Sort();
            $order = 'ASC';

            if (Str::startsWith($field, $this->sortDescSign)) {
                $order = 'DESC';
                $field = mb_substr($field, mb_strlen($this->sortDescSign));
            }

            if ($field = trim($field)) {
                $s->setField($field);
                $s->setOrder($order);
                $sorts->put($field, $s);
            }
        });

        return $sorts->values();
    }
}
