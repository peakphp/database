<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

class WhereInFilter extends WhereFilter
{
    public function __construct(string $column, array $values)
    {
        parent::__construct($column, $values);
    }
}