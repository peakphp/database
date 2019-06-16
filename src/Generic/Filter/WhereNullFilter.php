<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

class WhereNullFilter extends WhereFilter
{
    public function __construct(string $column)
    {
        parent::__construct($column, null, null);
    }
}