<?php

declare(strict_types=1);

namespace Peak\Database\Generic;

class QueryPagination implements QueryPaginationInterface
{
    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $qtyPerPage;

    /**
     * QueryPagination constructor.
     * @param string $orderBy
     * @param string $direction
     * @param int $page
     * @param int $qtyPerPage
     */
    public function __construct(string $orderBy, string $direction, int $page, int $qtyPerPage)
    {
        $this->orderBy = $orderBy;
        $this->direction = $direction;
        if ($page < 1) {
            $page = 1;
        }
        $this->page = $page;
        $this->qtyPerPage = $qtyPerPage;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getQtyPerPage(): int
    {
        return $this->qtyPerPage;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        if ($this->page < 2) {
            return 0;
        }

        return ($this->page - 1) * $this->qtyPerPage;
    }
}

