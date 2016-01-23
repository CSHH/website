<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;

final class GalleryPresenter extends SingleUserContentPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 10, $this->getLoggedUser());
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
            $this->getLoggedUser()
        );
    }
}
