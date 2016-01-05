<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class GamePresenter extends SharedContentPresenter
{
    /** @var Paginator */
    private $items;

    /** @var Entities\BaseEntity */
    private $item;

    public function actionDefault()
    {
        $items = $this->wikiCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_GAME);
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
    }
}
