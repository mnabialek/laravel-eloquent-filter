<?php

namespace Mnabialek\LaravelEloquentFilter;

class Sort implements Contracts\Sort
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
