<?php

namespace App\FrontModule\Presenters;

use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /**
     * @param  Repositories\BaseRepository $repository
     * @param  string                      $tagSlug
     * @param  int                         $limit
     * @param  bool                        $inactiveOnly
     * @return Paginator
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $tagSlug, $limit, $inactiveOnly)
    {
        $tag = $this->getTag($tagSlug);

        $canAccess = $this->canAccess();

        if ($canAccess && $inactiveOnly) {
            $items = $tag
                ? $repository->getAllInactiveByTagForPage($this->page, $limit, $tag)
                : $repository->getAllInactiveForPage($this->page, $limit);

        } else {
            $state = !$canAccess;

            $items = $tag
                ? $repository->getAllByTagForPage($this->page, $limit, $tag, $state)
                : $repository->getAllForPage($this->page, $limit, $state);
        }

        $this->preparePaginator($items->count(), $limit);

        $this->throw404IfNoItemsOnPage($items, $tag);

        $this->tag = $tag;

        return $items;
    }
}
