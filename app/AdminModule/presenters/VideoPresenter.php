<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class VideoPresenter extends SingleUserContentPresenter
{
    /** @var Paginator */
    private $items;

    /** @var Entities\BaseEntity */
    private $item;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        if ($id !== null) {
            $item = $this->videoCrud->getById($id);
            if (!$item) {
                $this->flashMessage($this->translator->translate('common.item.does_not_exist'));
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $items = $this->videoCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
    }

    /**
     * @return Forms\VideoForm
     */
    protected function createComponentForm()
    {
        return new Forms\VideoForm(
            $this->translator,
            $this->tagCrud,
            $this->videoCrud,
            $this->getLoggedUser(),
            $this->item
        );
    }
}
