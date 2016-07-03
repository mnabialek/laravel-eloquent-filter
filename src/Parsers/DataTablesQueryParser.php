<?php

namespace Mnabialek\LaravelEloquentFilter\Parsers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Objects\Sort;

class DataTablesQueryParser extends SimpleQueryParser
{
    /**
     * Input name that holds dataTables filters
     *
     * @var string
     */
    protected $dataTablesFilterName = 'columns';

    /**
     * Input name that holds dataTables sorts
     *
     * @var string
     */
    protected $dataTablesSortName = 'order';

    /**`
     * Operator used for dataTables filters
     *
     * @var string
     */
    protected $dataTablesFilterOperator = 'LIKE';

    /**
     * DataTablesQueryParser constructor.
     *
     * @param Request $request
     * @param Collection $collection
     */
    public function __construct(Request $request, Collection $collection)
    {
        parent::__construct($request, $collection);
        $this->addIgnoredFilter($this->dataTablesFilterName);
        $this->addIgnoredFilter($this->dataTablesSortName);
    }

    /**
     * Set default dataTables filter operator
     *
     * @param string $operator
     */
    public function setDataTablesFilterOperator($operator)
    {
        $this->dataTablesFilterOperator = $operator;
    }

    /**
     * Get filters
     *
     * @return Collection
     */
    public function getFilters()
    {
        $input = $this->collection->make(
            $this->request->input($this->dataTablesFilterName))
            ->pluck('search.value', 'data');

        $filters = $this->transformInputIntoFilters($input,
            $this->dataTablesFilterOperator);

        return $filters->merge(parent::getFilters());
    }

    /**
     * Get parameters for sorting
     *
     * @return Collection
     */
    public function getSorts()
    {
        $sortFields = $this->collection->make(
            $this->request->input($this->dataTablesSortName))
            ->pluck('dir', 'column');

        $fields = $this->collection->make(
            $this->request->input($this->dataTablesFilterName));

        $sorts = $this->collection->make();

        $sortFields->each(function ($order, $index) use ($sorts, $fields) {
            $fieldData = $fields->get($index);
            if (!empty($fieldData['data'])) {
                $field = $fieldData['data'];
                $order = mb_strtoupper($order);
                if (collect(['ASC', 'DESC'])->contains($order)) {
                    $sorts->put($field, new Sort($field, $order));
                }
            }
        });

        return $sorts->values()->merge(parent::getSorts());
    }
}
