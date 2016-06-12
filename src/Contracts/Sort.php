<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

interface Sort
{
    /**
     * Set sort field
     *
     * @param string $field
     */
    public function setField($field);

    /**
     * Get sort field
     *
     * @return string
     */
    public function getField();

    /**
     * Set order for field
     *
     * @param string $order
     */
    public function setOrder($order);

    /**
     * Get sort order for field
     *
     * @return string
     */
    public function getOrder();
}
