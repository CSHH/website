<?php

namespace App\Presenters;

use App\Model\Crud;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /**
     * @param  Crud\BaseCrud $crud
     * @param  string        $tagSlug
     * @param  int           $limit
     * @return Paginator
     */
    protected function runActionDefault(Crud\BaseCrud $crud, $tagSlug, $limit)
    {
        $tag = $this->getTag($tagSlug);

        $items = $tag
            ? $crud->getAllByTagForPage($this->page, $limit, $tag)
            : $crud->getAllForPage($this->page, $limit);

        $this->preparePaginator($items->count(), $limit);

        $this->throw404IfNoItemsOnPage($items, $tag);

        $this->tag = $tag;

        return $items;
    }
}
