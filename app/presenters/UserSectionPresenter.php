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

    public function actionVideos()
    {
        $items = $this->videoCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderVideos()
    {
        $this->template->items = $this->items;
    }

    public function actionGames()
    {
        $items = $this->wikiCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_GAME);
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderGames()
    {
        $this->template->items = $this->items;
    }

    public function actionMoview()
    {
        $items = $this->wikiCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_MOVIE);
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderMovies()
    {
        $this->template->items = $this->items;
    }

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

    /**
     * @return Entities\UserEntity
     */
    private function getLoggedUser()
    {
        return $this->userCrud->getById($this->getUser()->id);
    }
}
