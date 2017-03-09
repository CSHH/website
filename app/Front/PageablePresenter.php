<?php

namespace App\Front;

use App\Entities;
use App\Repositories;
use App\Presenters\PageableTrait;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends BasePresenter
{
    use PageableTrait;

    /** @var Repositories\TagRepository @inject */
    public $tagRepository;

    /** @var Entities\TagEntity */
    protected $tag;

    protected function runRenderDefault()
    {
        $this->template->tag = $this->tag;
    }

    /**
     * @param  string                  $tagSlug
     * @return Entities\TagEntity|null
     */
    protected function getTag($tagSlug)
    {
        return $tagSlug ? $this->tagRepository->getBySlug($tagSlug) : null;
    }

    /**
     * @param Entities\TagEntity $tag
     * @param string             $slug
     */
    protected function throw404IfNoTagOrSlug(Entities\TagEntity $tag, $slug)
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
        if ($tag && !$items || $this->vp->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }
    }
}
