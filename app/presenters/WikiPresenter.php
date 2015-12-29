<?php

namespace App\Presenters;

use App\Model\Entities;

abstract class WikiPresenter extends PageablePresenter
{
    /** @var Entities\WikiEntity[] */
    protected $wikis;

    /** @var Entities\WikiEntity */
    protected $wiki;

    /** @var Entities\TagEntity */
    protected $tag;

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
     * @param string $tagSlug
     * @param int    $limit
     * @param string $type
     */
    protected function runActionDefault($tagSlug, $limit, $type)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        $wikis = $tag
            ? $this->wikiCrud->getAllByTagForPage($this->page, $limit, $tag, $type)
            : $this->wikiCrud->getAllForPage($this->page, $limit, $type);

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
}
