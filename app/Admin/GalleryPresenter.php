<?php

namespace App\Admin;

use App\Components\Forms;

final class GalleryPresenter extends SingleUserContentPresenter
{
    /** @var Forms\GalleryFormInterface @inject */
    public $galleryForm;

    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 54, $this->getLoggedUserEntity());
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
        return $this->galleryForm->create($this->getLoggedUserEntity());
    }
}
