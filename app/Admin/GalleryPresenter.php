<?php

namespace App\Admin;

use App\Forms;

final class GalleryPresenter extends SingleUserContentPresenter
{
    /** @var Forms\GalleryFormInterface @inject */
    public $galleryForm;

    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 50, $this->loggedUser->getLoggedUserEntity());
    }

    /**
     * @param int $imageId
     */
    public function handleActivate($imageId)
    {
        $this->runHandleActivate($imageId, $this->imageRepository);
    }

    /**
     * @param int $imageId
     */
    public function handleDelete($imageId)
    {
        $this->runHandleDelete($imageId, $this->imageRepository);
    }

    /**
     * @return Forms\GalleryForm
     */
    protected function createComponentForm()
    {
        return $this->galleryForm->create($this->loggedUser->getLoggedUserEntity());
    }
}
