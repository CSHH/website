<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class BookPresenter extends SharedContentPresenter
{
    /** @var Paginator */
    private $items;

    /** @var Entities\BaseEntity */
    private $item;

    public function actionBooks()
    {
        $items = $this->wikiCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_BOOK);
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderBooks()
    {
        $this->template->items = $this->items;
    }
}
