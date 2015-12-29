<?php

namespace App\Presenters;

use App\Components\Controls;
use App\Model\Crud;
use App\Model\Entities;

class MoviePresenter extends BasePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Crud\WikiCrud @inject */
    public $wikiCrud;

    /** @var Entities\WikiEntity[] */
    private $wikis;

    /** @var Entities\WikiEntity */
    private $wiki;

    /** @var Entities\TagEntity */
    private $tag;

    /** @var Controls\VisualPaginator */
    private $vp;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        $limit = 10;

        $wikis = $tag
            ? $this->wikiCrud->getAllByTagForPage($this->page, $limit, $tag)
            : $this->wikiCrud->getAllForPage($this->page, $limit);

        $this->preparePaginator($wikis->count(), $limit);

        if ($tag && !$wikis || $this->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }

        $this->wikis = $wikis;
        $this->tag   = $tag;
    }

    public function renderDefault()
    {
        $this->template->wikis = $this->wikis;
        $this->template->tag   = $this->tag;
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        if (!$tag || !$slug) {
            $this->throw404();
        }

        $wiki = $this->wikiCrud->getByTagAndSlug($tag, $slug);

        if (!$wiki) {
            $this->throw404();
        }

        $this->wiki = $wiki;
    }

    public function renderDetail()
    {
        $this->template->wiki = $this->wiki;
    }

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
    private function preparePaginator($itemCount, $limit)
    {
        $this->vp = new Controls\VisualPaginator($this->page);
        $p        = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->page);
    }
}
