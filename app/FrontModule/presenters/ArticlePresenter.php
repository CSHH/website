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

        if ((!$article || !$article->isActive) && !$this->canAccess()) {
            $this->throw404();
        }

        $this->article = $article;
    }

    public function renderDetail()
    {
        $this->template->article = $this->article;
    }

    /**
     * @param int $articleId
     */
    public function handleActivate($articleId)
    {
        $article = $articleId ? $this->articleRepository->getById($articleId) : null;

        if (!$article) {
            $this->throw404();
        }

        $this->articleRepository->activate($article);

        $this->flashMessage($this->translator->translate('locale.item.activated'));
        $this->redirect('this');
    }

    /**
     * @param int $articleId
     */
    public function handleDelete($articleId)
    {
        $article = $articleId ? $this->articleRepository->getById($articleId) : null;

        if (!$article) {
            $this->throw404();
        }

        $this->articleRepository->delete($article);

        $this->flashMessage($this->translator->translate('locale.item.deleted'));
        $this->redirect('this');
    }
}
