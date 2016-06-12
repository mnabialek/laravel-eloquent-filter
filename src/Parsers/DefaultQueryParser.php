<?php

namespace Mnabialek\LaravelEloquentFilter\Parsers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Condition;
use Mnabialek\LaravelEloquentFilter\Contracts\InputParser;
use Mnabialek\LaravelEloquentFilter\Sort;

class DefaultQueryParser implements InputParser
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Name of input that holds sorting order
     *
     * @var string
     */
    protected $sortName = 'sort';

    /**
     * Separator for sort fields
     *
     * @var string
     */
    protected $sortFieldsSeparator = ',';

    /**
     * Sign to mark that field should be sorted in descending order
     *
     * @var string
     */
    protected $sortDescSign = '-';

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * DefaultQueryParser constructor.
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
     * Get conditions
     *
     * @return Collection
     */
    public function getConditions()
    {
        $input = $this->collection->make(
            $this->request->except($this->sortName));

        $conditions = $this->collection->make();

        $input->each(function ($value, $field) use ($conditions) {
            $condition = new Condition();
            $condition->setField($field);
            $condition->setValue($value);
            $conditions->push($condition);
        });

        return $conditions;
    }

    /**
     * Get parameters for sorting
     *
     * @return Collection
     */
    public function getSorts()
    {
        $sortFields = $this->collection->make(explode(
            $this->sortFieldsSeparator,
            $this->request->input($this->sortName, '')));

        $sorts = $this->collection->make();

        $sortFields->each(function ($field) use ($sorts) {
            $s = new Sort();
            $order = 'ASC';

            if (starts_with($field, $this->sortDescSign)) {
                $order = 'DESC';
                $field = mb_substr($field, mb_strlen($this->sortDescSign));
            }

            $s->setField($field);
            $s->setOrder($order);
            $sorts->push($s);
        });

        return $sorts;
    }
}
