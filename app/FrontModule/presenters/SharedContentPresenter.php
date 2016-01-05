<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;

abstract class SharedContentPresenter extends PageablePresenter
{
    /** @var Entities\WikiEntity[] */
    protected $wikis;

    /** @var Entities\WikiEntity */
    protected $wiki;

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

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
        $tag = $this->getTag($tagSlug);

        $wikis = $tag
            ? $this->wikiCrud->getAllByTagForPage($this->page, $limit, $tag, $type)
            : $this->wikiCrud->getAllForPage($this->page, $limit, $type);

        $this->preparePaginator($wikis->count(), $limit);

        $this->throw404IfNoItemsOnPage($wikis, $tag);

        $this->wikis = $wikis;
        $this->tag   = $tag;
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->wikis = $this->wikis;
    }
}
