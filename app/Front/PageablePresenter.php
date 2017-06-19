<?php

namespace App\Front;

use App\Entities;
use App\Presenters\PageableTrait;
use App\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends BasePresenter
{
    use PageableTrait;

    /** @var Repositories\TagRepository @inject */
    public $tagRepository;

    /** @var Entities\TagEntity */
    protected $tag;

    protected function startup()
    {
        parent::startup();

        $this->registerPaginator();
    }

    protected function runRenderDefault()
    {
        $this->template->canAccess = $this->canAccess;
        $this->template->tag       = $this->tag;
    }

    /**
     * @param  string                  $tagSlug
     * @return Entities\TagEntity|null
     */
    protected function getTag($tagSlug)
    {
        $tag = $tagSlug ? $this->tagRepository->getBySlug($tagSlug) : null;

        if ($tagSlug && $tag === null) {
            $this->throw404();
        }

        return $tag;
    }

    /**
     * @param Entities\TagEntity $tag
     * @param string             $slug
     */
    protected function throw404IfNoTagOrSlug(Entities\TagEntity $tag = null, $slug = null)
    {
        if (!$tag || !$slug) {
            $this->throw404();
        }
    }

    /**
     * @param Paginator          $items
     * @param Entities\TagEntity $tag
     */
    protected function throw404IfNoItemsOnPage(Paginator $items, Entities\TagEntity $tag = null)
    {
        if ($tag && $items->count() === 0 || $this->vp->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }
    }
}
