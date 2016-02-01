<?php

namespace App\FrontModule\Presenters;

use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /** @var string @persistent */
    public $inactiveOnly = 'no';

    /** @var bool */
    protected $displayInactiveOnly = false;

    /**
     * @param  Repositories\BaseRepository $repository
     * @param  string                      $tagSlug
     * @param  int                         $limit
     * @return Paginator
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $tagSlug, $limit)
    {
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }

        $tag = $this->getTag($tagSlug);

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
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

        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
    }
}
