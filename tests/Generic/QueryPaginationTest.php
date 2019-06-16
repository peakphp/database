<?php

use Peak\Database\Generic\QueryPagination;

class QueryPaginationTest extends \PHPUnit\Framework\TestCase
{

    public function testGeneral()
    {
        $qp = new QueryPagination(
            'col',
            'asc',
            1,
            10
        );

        $this->assertTrue($qp->getOrderBy() === 'col');
        $this->assertTrue($qp->getDirection() === 'asc');
        $this->assertTrue($qp->getPage() === 1);
        $this->assertTrue($qp->getQtyPerPage() === 10);
        $this->assertTrue($qp->getOffset() === 0);
    }

    public function testOutOfBoundPageNumber()
    {
        $qp = new QueryPagination(
            'col',
            'asc',
            0,
            12
        );
        $this->assertTrue($qp->getPage() === 1);

        $qp = new QueryPagination(
            'col',
            'asc',
            -165,
            12
        );

        $this->assertTrue($qp->getPage() === 1);
    }

    public function testOffset()
    {
        $qp = new QueryPagination(
            'col',
            'asc',
            4,
            12
        );
        $this->assertTrue($qp->getOffset() === 36);
    }

}