<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;

final class ArticlePresenter extends SingleUserContentPresenter
{
    /** @var Entities\ArticleEntity[] */
    private $articles;

    /** @var Entities\ArticleEntity */
    private $article;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->articles = $this->runActionDefault($this->articleRepository, $tagSlug, 10);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->articles = $this->articles;
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

        $article = $this->articleRepository->getByTagAndSlug($tag, $slug);

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
