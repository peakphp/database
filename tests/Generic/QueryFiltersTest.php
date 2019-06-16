<?php

use Peak\Database\Generic\Filter\WhereArrayFilterInterface;
use \Peak\Database\Generic\QueryFilters;

class QueryFiltersTest extends \PHPUnit\Framework\TestCase
{

    public function testGeneral()
    {
        $qf = new QueryFilters();

        $qf
            ->where('column','value', '=')
            ->orWhere('column','value', '=')
            ->whereIn('column', ['value1', 'value2'])
            ->orWhereIn('column', ['value1', 'value2'])
            ->whereNull('column')
            ->orWhereNull('column')
            ->whereNotNull('column')
            ->orWhereNotNull('column');

        $filters = $qf->getFilters();

        $this->assertCount(8, $filters);

        foreach ($filters as $filter) {
            $this->assertInstanceOf(\Peak\Database\Generic\Filter\WhereFilterInterface::class, $filter);
            $this->assertTrue($filter->getColumn() === 'column');
        }

        $this->assertTrue($filters[4]->getValue() === null);
        $this->assertTrue($filters[4]->getOperator() === null);
    }

    public function testSubArrayFilter()
    {
        $queryFilters = new QueryFilters();

        $subQueryFilters = new QueryFilters();
        $subQueryFilters->where('column','value', '=');

        $queryFilters
            ->where('column','value', '=')
            ->whereArray($subQueryFilters);

        $filters = $queryFilters->getFilters();

        $this->assertInstanceOf(\Peak\Database\Generic\Filter\WhereFilterInterface::class, $filters[0]);
        $this->assertInstanceOf(\Peak\Database\Generic\Filter\WhereArrayFilterInterface::class, $filters[1]);

        $this->assertCount(1, $filters[1]->getFilters());
    }

}