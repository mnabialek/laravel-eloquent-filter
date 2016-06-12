<?php

namespace Mnabialek\LaravelEloquentFilter\Contracts;

interface Condition
{
    /**
     * Get condition field
     *
     * @return string
     */
    public function getField();

    /**
     * Set condition field
     *
     * @param string $field
     */
    public function setField($field);

    /**
     * Get condition operator
     *
     * @return string
     */
    public function getOperator();

    /**
     * Set condition operator
     *
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * Get field value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set field value
     *
     * @param mixed $value
     */
    public function setValue($value);
}
