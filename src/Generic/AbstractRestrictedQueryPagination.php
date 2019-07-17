<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

abstract class AbstractRestrictedQueryPagination extends QueryPagination
{
    /**
     * @var array<string>
     */
    protected $allowedColumns = [];

    /**
     * @var array<string>
     */
    protected $allowedDirections = ['asc', 'desc'];

    /**
     * @return string
     * @throws \Exception
     */
    public function getOrderBy(): string
    {
        $value = parent::getOrderBy();
        if (!in_array($value, $this->allowedColumns)) {
            throw new \Exception('Expected orderBy to be '.implode(' or ', $this->allowedColumns).'. Received "'.$value.'"');
        }
        return $value;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDirection(): string
    {
        $value = parent::getDirection();
        if (!in_array($value, $this->allowedDirections)) {
            throw new \Exception('Expected direction to be '.implode(' or ', $this->allowedDirections).'. Received "'.$value.'"');
        }
        return $value;
    }
}