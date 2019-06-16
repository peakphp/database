<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

abstract class AbstractRestrictedQueryFilters extends QueryFilters
{
    /**
     * @var array<string>
     */
    protected $allowedColumns = [];

    /**
     * @var array<string>
     */
    protected $allowedOperators = ['=', '>', '<', 'like'];

    /**
     * @param string $column
     * @param string $operator
     * @throws \Exception
     */
    private function validateBoth(string $column, string $operator)
    {
        $this->validateColumn($column);
        $this->validateOperator($operator);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function validateColumn(string $column)
    {
        if (!in_array($column, $this->allowedColumns)) {
            throw new \Exception('Expected column to be '.implode('or ', $this->allowedColumns).'. Received "'.$column.'"');
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function validateOperator(string $operator)
    {
        if (!in_array($operator, $this->allowedOperators)) {
            throw new \Exception('Expected operator to be '.implode('or ', $this->allowedOperators).'. Received "'.$operator.'"');
        }
    }

    /**
     * @param string $column
     * @param $value
     * @param string $operator
     * @return mixed
     * @throws \Exception
     */
    public function where(string $column, $value, string $operator = '=')
    {
        $this->validateBoth($column, $operator);
        return parent::where($column, $value, $operator);
    }

    /**
     * @param string $column
     * @param $value
     * @param string $operator
     * @return mixed
     * @throws \Exception
     */
    public function orWhere(string $column, $value, string $operator = '=')
    {
        $this->validateBoth($column, $operator);
        return parent::orWhere($column, $value, $operator);
    }

    /**
     * @param string $column
     * @param array $values
     * @return mixed
     * @throws \Exception
     */
    public function whereIn(string $column, array $values)
    {
        $this->validateColumn($column);
        return parent::whereIn($column, $values);
    }

    /**
     * @param $column
     * @param $values
     * @return mixed
     * @throws \Exception
     */
    public function orWhereIn($column, $values)
    {
        $this->validateColumn($column);
        return parent::orWhereIn($column, $values);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function whereNull(string $column)
    {
        $this->validateColumn($column);
        return parent::whereNull($column);
    }

    /**
     * @param $column
     * @return mixed
     * @throws \Exception
     */
    public function orWhereNull($column)
    {
        $this->validateColumn($column);
        return parent::orWhereNull($column);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function whereNotNull(string $column)
    {
        $this->validateColumn($column);
        return parent::whereNotNull($column);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function orWhereNotNull(string $column)
    {
        $this->validateColumn($column);
        return parent::orWhereNotNull($column);
    }
}