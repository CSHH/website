<?php

namespace App\Presenters;

use App\Model\Crud;
use App\Model\Entities;

final class ArticlePresenter extends PageablePresenter
{
    /** @var Crud\ArticleCrud @inject */
    public $articleCrud;

    /** @var Entities\ArticleEntity[] */
    private $articles;

    /** @var Entities\ArticleEntity */
    private $article;

    /** @var Entities\TagEntity */
    private $tag;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        $limit = 10;

        $articles = $tag
            ? $this->articleCrud->getAllByTagForPage($this->page, $limit, $tag)
            : $this->articleCrud->getAllForPage($this->page, $limit);

        $this->preparePaginator($articles->count(), $limit);

        if ($tag && !$articles || $this->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }

        $this->articles = $articles;
        $this->tag      = $tag;
    }

    public function renderDefault()
    {
        $this->template->articles = $this->articles;
        $this->template->tag      = $this->tag;
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

        $article = $this->articleCrud->getByTagAndSlug($tag, $slug);

        if (!$article) {
            $this->throw404();
        }

        $this->article = $article;
    }

    public function renderDetail()
    {
        $this->template->article = $this->article;
    }
}
