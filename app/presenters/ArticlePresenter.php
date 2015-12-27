<?php

namespace App\Presenters;

use App\Model\Crud;
use App\Model\Entities;
use App\Components\Controls;

class ArticlePresenter extends BasePresenter
{
	/** @var int @persistent */
	public $page = 1;

	/** @var Crud\ArticleCrud @inject */
    public $articleCrud;

    /** @var Entities\ArticleEntity[] */
    private $articles;

    /** @var Entities\ArticleEntity */
    private $article;

	/** @var Controls\VisualPaginator */
	private $vp;

	/**
     * @param string $tag
     */
    public function actionDefault($tag)
    {
		$limit = 10;

        $articles = $tag
            ? $this->articleCrud->getAllByTagForPage($this->page, $limit, $tag)
            : $this->articleCrud->getAllForPage($this->page, $limit);

		$this->vp = new Controls\VisualPaginator($this->page);
		$p = $this->vp->getPaginator();
		$p->setItemCount($articles->count());
		$p->setItemsPerPage($limit);
		$p->setPage($this->page);

        if ($tag && !$articles || $this->page > $p->getLastPage()) {
            $this->throw404();
        }

        $this->articles = $articles;
    }

	/**
     * @param string $tag
     */
    public function renderDefault($tag)
    {
        $this->template->articles = $this->articles;
        $this->template->tag      = $tag;
    }

    /**
     * @param string $tag
     * @param string $slug
     */
    public function actionDetail($tag, $slug)
    {
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

	/**
	 * @return Controls\VisualPaginator
	 */
	protected function createComponentVp()
	{
		return $this->vp;
	}
}
