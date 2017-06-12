<?php

namespace App\Admin;

use App\Forms;

final class ArticleDetailPresenter extends SingleUserContentDetailPresenter
{
    /** @var Forms\ArticleFormInterface @inject */
    public $articleForm;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->articleRepository, 'ArticleListing:default', $id);
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->articleRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'ArticleListing:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $id
     */
    public function actionActivate($id)
    {
        $this->runHandleActivate($id, $this->articleRepository, 'ArticleListing:default');
    }

    /**
     * @param int $id
     */
    public function actionDelete($id)
    {
        $this->runHandleDelete($id, $this->articleRepository, 'ArticleListing:default');
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
