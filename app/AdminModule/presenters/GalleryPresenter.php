<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;

final class GalleryPresenter extends SingleUserContentPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault($this->imageRepository, 10, $this->getLoggedUserEntity());
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

    /**
     * @param int $imageId
     */
    public function handleActivate($imageId)
    {
        $image = $imageId ? $this->imageRepository->getById($imageId) : null;

        if (!$image) {
            $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
            $this->redirect('this');
        }

        $this->imageRepository->activate($image);

        $this->flashMessage($this->translator->translate('locale.item.activated'));
        $this->redirect('this');
    }

    /**
     * @param int $imageId
     */
    public function handleDelete($imageId)
    {
        $image = $imageId ? $this->imageRepository->getById($imageId) : null;

        if (!$image) {
            $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
            $this->redirect('this');
        }

        $this->imageRepository->delete($image);

        $this->flashMessage($this->translator->translate('locale.item.deleted'));
        $this->redirect('this');
    }
}
