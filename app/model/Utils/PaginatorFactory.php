<?php

namespace App\Model\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorFactory
{
    /**
     * @param  Query|QueryBuilder $query
     * @return Paginator
     */
    public function createPaginator($query)
    {
        return new Paginator($query);
    }
}
