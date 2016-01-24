<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;

final class ArticlePresenter extends SingleUserContentPresenter
{
    /** @var Entities\BaseEntity */
    private $item;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        if ($id !== null) {
            $item = $this->articleRepository->getById($id);
            $user = $this->getLoggedUserEntity();
            if (!$item || $item->user->id !== $user->id) {
                $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
                $this->redirect('Article:default');
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->articleRepository, 10, $this->getLoggedUserEntity());
    }

    /**
     * @return Forms\ArticleForm
     */
    protected function createComponentForm()
    {
        return new Forms\ArticleForm(
            $this->translator,
            $this->tagRepository,
            $this->articleRepository,
            $this->getLoggedUserEntity(),
            $this->item
        );
    }

    /**
     * @param int $articleId
     */
    public function handleActivate($articleId)
    {
        $article = $articleId ? $this->articleRepository->getById($articleId) : null;

        if (!$article) {
            $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
            $this->redirect('this');
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
            $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
            $this->redirect('this');
        }

        $this->articleRepository->delete($article);

        $this->flashMessage($this->translator->translate('locale.item.deleted'));
        $this->redirect('this');
    }
}
