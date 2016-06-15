<?php

namespace Mnabialek\LaravelEloquentFilter\Parsers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Objects\Filter;
use Mnabialek\LaravelEloquentFilter\Contracts\InputParser;
use Mnabialek\LaravelEloquentFilter\Objects\Sort;

class SimpleQueryParser extends QueryParser implements InputParser
{
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
     * SimpleQueryParser constructor.
     *
     * @param Request $request
     * @param Collection $collection
     */
    public function __construct(Request $request, Collection $collection)
    {
        parent::__construct($request, $collection);
        $this->addIgnoredFilter($this->sortName);
    }

    /**
     * Get filters
     *
     * @return Collection
     */
    public function getFilters()
    {
        $input = $this->collection->make(
            $this->request->except($this->getIgnoredFilters()));

        $filters = $this->collection->make();

        $this->filterEmptyValues($input)
            ->each(function ($value, $field) use ($filters) {
                $filters->push(new Filter($field, $value,
                    $this->getDefaultOperator()));
            });

        return $filters;
    }

    /**
     * Get parameters for sorting
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
            $order = 'ASC';

            if (starts_with($field, $this->sortDescSign)) {
                $order = 'DESC';
                $field = mb_substr($field, mb_strlen($this->sortDescSign));
            }

            if ($field = trim($field)) {
                $sorts->put($field, new Sort($field, $order));
            }
        });

        return $sorts->values();
    }
}
