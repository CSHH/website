<?php

namespace App\Admin;

final class ArticleListingPresenter extends SingleUserContentListingPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault($this->articleRepository, 10, $this->loggedUser->getLoggedUserEntity());
    }
}
