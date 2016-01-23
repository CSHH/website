<?php

namespace App\FrontModule\Presenters;

use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /** @var bool */
    protected $inactiveOnly = false;

    /**
     * @param  Repositories\BaseRepository $repository
     * @param  string                      $tagSlug
     * @param  int                         $limit
     * @return Paginator
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $tagSlug, $limit)
    {
        $this->inactiveOnly = $this->displayInactiveOnly();

        $tag = $this->getTag($tagSlug);

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->inactiveOnly) {
            $items = $tag
                ? $repository->getAllInactiveByTagForPage($this->page, $limit, $tag)
                : $repository->getAllInactiveForPage($this->page, $limit);

        } else {
            $state = !$this->canAccess;

            $items = $tag
                ? $repository->getAllByTagForPage($this->page, $limit, $tag, $state)
                : $repository->getAllForPage($this->page, $limit, $state);
        }

        $this->preparePaginator($items->count(), $limit);

        $this->throw404IfNoItemsOnPage($items, $tag);

        $this->tag = $tag;

        return $items;
    }

    protected function runRenderDefault()
    {
        parent::runRenderDefault();

        $this->template->inactiveOnly = $this->inactiveOnly;
        $this->template->canAccess    = $this->canAccess;
    }

    /**
     * @return bool
     */
    private function displayInactiveOnly()
    {
        return $this->getHttpRequest()->getQuery('inactiveOnly') === '' ? true : false;
    }
}
