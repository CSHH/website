<?php

namespace App\AdminModule\Presenters;

use App\Components\Controls;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends SecurePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Controls\VisualPaginator */
    protected $vp;

    /** @var Entities\TagEntity */
    protected $tag;

    /**
     * @return Controls\VisualPaginator
     */
    protected function createComponentVp()
    {
        return $this->vp;
    }

    /**
     * @param int $itemCount
     * @param int $limit
     */
    protected function preparePaginator($itemCount, $limit)
    {
        $this->vp = new Controls\VisualPaginator($this->page);
        $p        = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->page);
    }

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
        if ($tag && !$items || $this->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }
    }
}
