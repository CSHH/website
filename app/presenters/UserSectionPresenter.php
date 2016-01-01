<?php

namespace App\Presenters;

use App\Model\Crud;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class UserSectionPresenter extends SecurePresenter
{
    /** @var Crud\UserCrud @inject */
    public $userCrud;

    /** @var Paginator */
    private $items;

    public function actionArticles()
    {
        $items = $this->articleCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderArticles()
    {
        $this->template->items = $this->items;
    }

    public function actionGalleries()
    {
        $items = $this->imageCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderGalleries()
    {
        $this->template->items = $this->items;
    }

    /**
     * @return Entities\UserEntity
     */
    private function getLoggedUser()
    {
        return $this->userCrud->getById($this->getUser()->id);
    }
}
