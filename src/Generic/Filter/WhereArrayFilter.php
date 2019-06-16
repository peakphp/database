<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

use Peak\Database\Generic\QueryFiltersInterface;

class WhereArrayFilter implements WhereArrayFilterInterface
{
    /**
     * @var QueryFiltersInterface
     */
    private $filters;

    public function __construct(QueryFiltersInterface $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters(): QueryFiltersInterface
    {
        return $this->filters;
    }
}