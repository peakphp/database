<?php

use Peak\Database\Generic\AbstractRestrictedQueryFilters;

class RestrictedQueryFiltersTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @throws Exception
     */
    public function testGeneral()
    {
        $qf = (new UserFilters())
            ->where('id', 1, '=');

        $this->assertInstanceOf(\Peak\Database\Generic\QueryFilters::class, $qf);
    }

    /**
     * @throws Exception
     */
    public function testOrderByException()
    {
        $this->expectException(\Exception::class);
        $qf = (new UserFilters())
            ->where('unknown', 1, '=');
    }
}


class UserFilters extends AbstractRestrictedQueryFilters
{
    protected $allowedColumns = ['id', 'name', 'email'];
    protected $allowedOperator = ['='];
}