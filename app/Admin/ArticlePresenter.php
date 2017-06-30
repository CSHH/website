<?php

namespace App\Admin;

use App\Forms;
use App\Repositories;

final class ArticlePresenter extends SingleUserContentPresenter
{
    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Forms\ArticleFormInterface @inject */
    public $articleForm;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->articleRepository, 'Article:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->articleRepository, 10, $this->loggedUser->getLoggedUserEntity());
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->articleRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'Article:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $articleId
     */
    public function handleActivate($articleId)
    {
        $this->runHandleActivate($articleId, $this->articleRepository);
    }

    /**
     * @param int $articleId
     */
    public function handleDelete($articleId)
    {
        $this->runHandleDelete($articleId, $this->articleRepository);
    }

    /**
     * @return Forms\ArticleForm
     */
    protected function createComponentForm()
    {
        return $this->articleForm->create(
            $this->loggedUser->getLoggedUserEntity(),
            $this->item
        );
    }
}
