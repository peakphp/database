<?php

use Peak\Database\Generic\AbstractRestrictedQueryPagination;

class RestrictedQueryPaginationTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @throws Exception
     */
    public function testGeneral()
    {
        $qp = new UserPagination(
            'id',
            'asc',
            1,
            10
        );

        $this->assertTrue($qp->getOrderBy() === 'id');
        $this->assertTrue($qp->getDirection() === 'asc');
    }

    /**
     * @throws Exception
     */
    public function testOrderByException()
    {
        $this->expectException(\Exception::class);
        $qp = new UserPagination(
            'unknow',
            'asc',
            1,
            10
        );
        $qp->getOrderBy();
    }

    /**
     * @throws Exception
     */
    public function testDirectionException()
    {
        $this->expectException(\Exception::class);
        $qp = new UserPagination(
            'id',
            'something',
            1,
            10
        );
        $qp->getDirection();
    }

    /**
     * @throws Exception
     */
    public function testPrefixedTableColumnName()
    {
        $qp = new UserPagination(
            'user.id',
            'asc',
            1,
            10
        );

        $this->assertTrue($qp->getOrderBy() === 'user.id');

        $qp = new UserPagination(
            '`user`.`id`',
            'asc',
            1,
            10
        );

        $this->assertTrue($qp->getOrderBy() === '`user`.`id`');


        $this->expectException(\Exception::class);
        $qp = new UserPagination(
            '`user`.`unknown`',
            'asc',
            1,
            10
        );

        $this->assertTrue($qp->getOrderBy() === '`user`.`id`');
    }

}


class UserPagination extends AbstractRestrictedQueryPagination
{
    protected $allowedColumns = ['id', 'name', 'email'];
}