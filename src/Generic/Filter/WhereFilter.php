<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

class WhereFilter implements WhereFilterInterface
{
    /**
     * @var string
     */
    private $column;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|null
     */
    private $operator;

    /**
     * WhereFilter constructor.
     * @param string $column
     * @param mixed $value
     * @param string|null $operator
     */
    public function __construct(string $column, $value, ?string $operator = '=')
    {
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }
}