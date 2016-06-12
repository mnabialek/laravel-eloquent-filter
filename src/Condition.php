<?php

namespace Mnabialek\LaravelEloquentFilter;

class Condition implements \Mnabialek\LaravelEloquentFilter\Contracts\Condition
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
