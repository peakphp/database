<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

interface QueryPaginationInterface
{
    public function getOrderBy(): string;
    public function getDirection(): string;
    public function getPage(): int;
    public function getQtyPerPage(): int;
    public function getOffset(): int;
}
