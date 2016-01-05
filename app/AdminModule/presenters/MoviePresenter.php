<?php

namespace App\AdminModule\Presenters;

use App\Components\Forms;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class MoviePresenter extends SharedContentPresenter
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
            $item = $this->wikiCrud->getById($id);
            if (!$item) {
                $this->flashMessage($this->translator->translate('common.item.does_not_exist'));
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $items = $this->wikiCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_MOVIE);
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
    }

    /**
     * @return Forms\WikiForm
     */
    protected function createComponentForm()
    {
        return new Forms\WikiForm(
            $this->translator,
            $this->tagCrud,
            $this->wikiCrud,
            $this->getLoggedUser(),
            Entities\WikiEntity::TYPE_MOVIE,
            $this->item
        );
    }
}
