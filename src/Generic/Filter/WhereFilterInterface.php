<?php

declare(strict_types=1);

namespace Peak\Database\Generic\Filter;

interface WhereFilterInterface
{
    public function getColumn(): string;
    public function getValue();
    public function getOperator(): ?string;
}