<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

interface Filter
{
    /**
     * Get filter field.
     *
     * @return string
     */
    public function getField();

    /**
     * Set filter field.
     *
     * @param string $field
     */
    public function setField($field);

    /**
     * Get filter operator.
     *
     * @return string
     */
    public function getOperator();

    /**
     * Set filter operator.
     *
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * Get filter field value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set filter field value.
     *
     * @param mixed $value
     */
    public function setValue($value);
}
