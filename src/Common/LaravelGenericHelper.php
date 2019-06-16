<?php

declare(strict_types=1);

namespace Peak\Database\Common;

use Peak\Database\Generic\Filter\OrWhereArrayFilter;
use Peak\Database\Generic\Filter\OrWhereFilter;
use Peak\Database\Generic\Filter\OrWhereInFilter;
use Peak\Database\Generic\Filter\OrWhereNotNullFilter;
use Peak\Database\Generic\Filter\OrWhereNullFilter;
use Peak\Database\Generic\Filter\WhereArrayFilter;
use Peak\Database\Generic\Filter\WhereFilter;
use Peak\Database\Generic\Filter\WhereFilterInterface;
use Peak\Database\Generic\Filter\WhereInFilter;
use Peak\Database\Generic\Filter\WhereNotNullFilter;
use Peak\Database\Generic\Filter\WhereNullFilter;
use Peak\Database\Generic\QueryFiltersInterface;
use Peak\Database\Generic\QueryPaginationInterface;
use Illuminate\Database\Query\Builder;

/**
 * The purpose of this class of to map generic query filter to specific laravel database query builder things
 */
class LaravelGenericHelper
{
    private static $filtersMapping = [
        OrWhereArrayFilter::class => 'orWhere',
        OrWhereFilter::class => 'orWhere',
        OrWhereInFilter::class => 'orWhereIn',
        OrWhereNotNullFilter::class => 'orWhereNotNull',
        OrWhereNullFilter::class => 'orWhereNull',
        WhereArrayFilter::class => 'where',
        WhereFilter::class => 'where',
        WhereInFilter::class => 'whereIn',
        WhereNotNullFilter::class => 'whereNotNull',
        WhereNullFilter::class => 'whereNull',
    ];

    /**
     * @param Builder $qb
     * @param QueryFiltersInterface $queryFilters
     * @return Builder
     * @throws \Exception
     */
    public static function filterQuery(Builder $qb, QueryFiltersInterface $queryFilters): Builder
    {
        /** @var WhereFilterInterface $filter */
        foreach ($queryFilters as $filter) {

            $filterClass = get_class($filter);
            $whereMethod = self::$filtersMapping[$filterClass];

            if (in_array($whereMethod, ['where', 'orWhere'])) {
                if ($filterClass === OrWhereArrayFilter::class || $filterClass === WhereArrayFilter::class) {
                    /** @var WhereArrayFilter $filter */
                    $qb->$whereMethod(function($query) use ($filter) {
                        return self::filterQuery($query, $filter->getFilters());
                    });
                } else {
                    $qb->$whereMethod($filter->getColumn(), $filter->getOperator(), self::prepareSoftValue($filter));
                }
            } elseif (in_array($whereMethod, ['whereIn', 'orWhereIn'])) {
                $qb->$whereMethod($filter->getColumn(), $filter->getValue());
            } elseif (in_array($whereMethod, ['whereNotNull', 'orWhereNotNull', 'whereNull', 'orWhereNull'])) {
                $qb->$whereMethod($filter->getColumn());
            }
        }

        return $qb;
    }

    /**
     * @param Builder $qb
     * @param QueryPaginationInterface $queryPagination
     * @return Builder
     */
    public static function paginateQuery(Builder $qb, QueryPaginationInterface $queryPagination): Builder
    {
        return $qb
            ->orderBy($queryPagination->getOrderBy(), $queryPagination->getDirection())
            ->offset($queryPagination->getOffset())
            ->limit($queryPagination->getQtyPerPage());
    }

    /**
     * @param WhereFilterInterface $whereFilter
     * @return array|string
     */
    private static function prepareSoftValue(WhereFilterInterface $whereFilter)
    {
        $value = $whereFilter->getValue();

        if (strtolower($whereFilter->getOperator()) === 'like') {
            if (is_array($value)) {
                foreach ($value as $i => $v) {
                    $value[$i] = '%'.$v.'%';
                }
            } else {
                $value = '%'.$value.'%';
            }
        }

        return $value;
    }
}
