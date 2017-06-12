<?php

namespace App\Admin;

final class GalleryListingPresenter extends SingleUserContentListingPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 50, $this->loggedUser->getLoggedUserEntity());
    }
}
