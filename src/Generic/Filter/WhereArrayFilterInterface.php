<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

use Peak\Database\Generic\QueryFiltersInterface;

interface WhereArrayFilterInterface
{
    public function getFilters(): QueryFiltersInterface;
}