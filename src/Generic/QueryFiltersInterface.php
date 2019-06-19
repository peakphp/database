<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

interface QueryFiltersInterface extends \IteratorAggregate
{
    public function getFilters(): array;

    public function getColumns(): array;
    public function setColumns(array $columns);

    public function where(string $column, $value, string $operator = '=');
    public function orWhere(string $column, $value, string $operator = '=');

    public function whereArray(QueryFiltersInterface $queryFilters);
    public function orWhereArray(QueryFiltersInterface $queryFilters);

    public function whereIn(string $column, array $values);
    public function orWhereIn(string $column, array $values);

    public function whereNull(string $column);
    public function orWhereNull(string $column);

    public function whereNotNull(string $column);
    public function orWhereNotNull(string $column);
}
