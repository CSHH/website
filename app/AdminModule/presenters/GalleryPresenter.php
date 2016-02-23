<?php

namespace App\AdminModule\Presenters;

use App\Components\Forms;

final class GalleryPresenter extends SingleUserContentPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 10, $this->getLoggedUserEntity());
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
        return new Forms\GalleryForm(
            $this->translator,
            $this->tagRepository,
            $this->imageRepository,
            $this->getLoggedUserEntity()
        );
    }
}
