<?php

namespace AppTests;

use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginatorToArrayConverter
{
    /**
     * @param  Paginator $paginator
     * @return array
     */
    private function paginatorToArray(Paginator $paginator)
    {
        return $paginator->getIterator()->getArrayCopy();
    }
}
