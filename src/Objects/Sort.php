<?php

namespace Mnabialek\LaravelEloquentFilter\Objects;

use Mnabialek\LaravelEloquentFilter\Contracts\Sort as SortContract;

class Sort implements SortContract
{
    /**
     * Sort field
     *
     * @var string
     */
    protected $field;

    /**
     * Order for field
     *
     * @var string
     */
    protected $order;

    /**
     * Sort constructor.
     *
     * @param string|null $field
     * @param string|null $order
     */
    public function __construct($field = null, $order = null)
    {
        $this->setField($field);
        $this->setOrder($order);
    }

    /**
     * {@inheritdoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}
