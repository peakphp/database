<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

use Peak\Database\Generic\Filter\OrWhereArrayFilter;
use Peak\Database\Generic\Filter\OrWhereFilter;
use Peak\Database\Generic\Filter\OrWhereInFilter;
use Peak\Database\Generic\Filter\OrWhereNotNullFilter;
use Peak\Database\Generic\Filter\OrWhereNullFilter;
use Peak\Database\Generic\Filter\WhereArrayFilter;
use Peak\Database\Generic\Filter\WhereFilter;
use Peak\Database\Generic\Filter\WhereInFilter;
use Peak\Database\Generic\Filter\WhereNotNullFilter;
use Peak\Database\Generic\Filter\WhereNullFilter;

class QueryFilters implements QueryFiltersInterface
{
    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array<string>
     */
    private $columns = [];

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->filters);
    }

    public function where(string $column, $value, string $operator = '=')
    {
        $this->filters[] = new WhereFilter($column, $value, $operator);
        return $this;
    }

    public function orWhere(string $column, $value, string $operator = '=')
    {
        $this->filters[] = new OrWhereFilter($column, $value, $operator);
        return $this;
    }

    public function whereArray(QueryFiltersInterface $queryFilters)
    {
        $this->filters[] = new WhereArrayFilter($queryFilters);
        return $this;
    }

    public function orWhereArray(QueryFiltersInterface $queryFilters)
    {
        $this->filters[] = new OrWhereArrayFilter($queryFilters);
        return $this;
    }

    public function whereIn(string $column, array $values)
    {
        $this->filters[] = new WhereInFilter($column, $values);
        return $this;
    }

    public function orWhereIn(string $column, array $values)
    {
        $this->filters[] = new OrWhereInFilter($column, $values);
        return $this;
    }

    public function whereNull(string $column)
    {
        $this->filters[] = new WhereNullFilter($column);
        return $this;
    }

    public function orWhereNull(string $column)
    {
        $this->filters[] = new OrWhereNullFilter($column);
        return $this;
    }

    public function whereNotNull(string $column)
    {
        $this->filters[] = new WhereNotNullFilter($column);
        return $this;
    }

    public function orWhereNotNull(string $column)
    {
        $this->filters[] = new OrWhereNotNullFilter($column);
        return $this;
    }


}
