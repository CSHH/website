<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class GalleryPresenter extends SingleUserContentPresenter
{
    /** @var Paginator */
    private $items;

    public function actionDefault()
    {
        $items = $this->imageRepository->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
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
