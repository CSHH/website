<?php

namespace App\Admin;

use App\Forms;

final class GalleryDetailPresenter extends SingleUserContentDetailPresenter
{
    /** @var Forms\GalleryFormInterface @inject */
    public $galleryForm;

    /**
     * @param int $id
     */
    public function actionActivate($id)
    {
        $this->runHandleActivate($id, $this->imageRepository, 'GalleryListing:default');
    }

    /**
     * @param int $id
     */
    public function actionDelete($id)
    {
        $this->runHandleDelete($id, $this->imageRepository, 'GalleryListing:default');
    }

    /**
     * @return Forms\GalleryForm
     */
    protected function createComponentForm()
    {
        return $this->galleryForm->create($this->loggedUser->getLoggedUserEntity());
    }
}
