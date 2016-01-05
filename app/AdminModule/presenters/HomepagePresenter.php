<?php

namespace App\AdminModule\Presenters;

use App\Components\Forms;
use App\Model\Crud;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class HomepagePresenter extends SecurePresenter
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

    public function renderArticles()
    {
        $this->template->items = $this->items;
    }

    /**
     * @return Forms\ArticleForm
     */
    protected function createComponentArticleForm()
    {
        return new Forms\ArticleForm(
            $this->translator,
            $this->tagCrud,
            $this->articleCrud,
            $this->getLoggedUser(),
            $this->item
        );
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
     * @return Forms\GalleryForm
     */
    protected function createComponentGalleryForm()
    {
        return new Forms\GalleryForm(
            $this->translator,
            $this->tagCrud,
            $this->imageCrud,
            $this->getLoggedUser()
        );
    }

    /**
     * @param int $id
     */
    public function actionVideoForm($id = null)
    {
        if ($id !== null) {
            $item = $this->videoCrud->getById($id);
            if (!$item) {
                $this->flashMessage($this->translator->translate('common.item.does_not_exist'));
            }

            $this->item = $item;
        }
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

    /**
     * @return Forms\VideoForm
     */
    protected function createComponentVideoForm()
    {
        return new Forms\VideoForm(
            $this->translator,
            $this->tagCrud,
            $this->videoCrud,
            $this->getLoggedUser(),
            $this->item
        );
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
