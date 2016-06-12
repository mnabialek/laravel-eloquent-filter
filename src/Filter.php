<?php

namespace Mnabialek\LaravelEloquentFilter;

use Mnabialek\LaravelEloquentFilter\Contracts\Filter as FilterContract;

class Filter implements FilterContract
{
    /**
     * Field
     *
     * @var string
     */
    protected $field;

    /**
     * Operator
     *
     * @var string
     */
    protected $operator;

    /**
     * Value for field
     *
     * @var mixed
     */
    protected $value;

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
    public function getOperator()
    {
        return $this->operator ?: '=';
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
