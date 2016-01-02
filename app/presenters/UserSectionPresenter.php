<?php

namespace App\Presenters;

use App\Components\Forms;
use App\Model\Crud;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class UserSectionPresenter extends SecurePresenter
{
    /** @var Crud\UserCrud @inject */
    public $userCrud;

    /** @var Paginator */
    private $items;

    /** @var Entities\BaseEntity */
    private $item;

    /**
     * @param int $id
     */
    public function actionArticleForm($id = null)
    {
        if ($id !== null) {
            $item = $this->articleCrud->getById($id);
            if (!$item) {
                $this->flashMessage($this->translator->translate('common.item.does_not_exist'));
            }

            $this->item = $item;
        }
    }

    public function actionArticles()
    {
        $items = $this->articleCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    /**
     * @return Forms\ArticleForm
     */
    protected function createComponentArticleForm()
    {
        return new Forms\ArticleForm(
            $this->translator,
            $this->articleCrud,
            $this->item
        );
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

    public function actionMovies()
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
