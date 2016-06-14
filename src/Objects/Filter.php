<?php

namespace Mnabialek\LaravelEloquentFilter\Objects;

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
     * Filter constructor.
     *
     * @param string|null $field
     * @param mixed $value
     * @param string|null $operator
     */
    public function __construct($field = null, $value = null, $operator = null)
    {
        $this->setField($field);
        $this->setValue($value);
        $this->setOperator($operator);
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
