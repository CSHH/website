<?php

namespace App\FrontModule\Presenters;

use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /**
     * @param  Repositories\BaseRepository $repository
     * @param  string        $tagSlug
     * @param  int           $limit
     * @return Paginator
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $tagSlug, $limit)
    {
        $tag = $this->getTag($tagSlug);

        $state = !$this->canAccess();

        $items = $tag
            ? $repository->getAllByTagForPage($this->page, $limit, $tag, $state)
            : $repository->getAllForPage($this->page, $limit, $state);

        $this->preparePaginator($items->count(), $limit);

        $this->throw404IfNoItemsOnPage($items, $tag);

        $this->tag = $tag;

        return $items;
    }
}
