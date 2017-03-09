<?php

namespace App\FrontModule\Presenters;

use App\Repositories;
use App\Utils\PaginatorFactory;
use App\Presenters\ActivityTrait;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    use ActivityTrait;

    /** @var PaginatorFactory @inject */
    public $paginatorFactory;

    /**
     * @param  Repositories\BaseRepository $repository
     * @param  string                      $tagSlug
     * @param  int                         $limit
     * @return Paginator
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $tagSlug, $limit)
    {
        $this->checkIfDisplayInactiveOnly();

        $tag = $this->getTag($tagSlug);

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $items = $tag
                ? $repository->getAllInactiveByTagForPage($this->paginatorFactory, $this->vp->page, $limit, $tag)
                : $repository->getAllInactiveForPage($this->paginatorFactory, $this->vp->page, $limit);

        } else {
            $state = !$this->canAccess;

            $items = $tag
                ? $repository->getAllByTagForPage($this->paginatorFactory, $this->vp->page, $limit, $tag, $state)
                : $repository->getAllForPage($this->paginatorFactory, $this->vp->page, $limit, $state);
        }

        $this->preparePaginator($items->count(), $limit);

        $this->throw404IfNoItemsOnPage($items, $tag);

        $this->tag = $tag;

        return $items;
    }

    protected function runRenderDefault()
    {
        parent::runRenderDefault();

        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
    }
}
