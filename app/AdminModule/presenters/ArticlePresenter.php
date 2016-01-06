<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ArticlePresenter extends SingleUserContentPresenter
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
            $item = $this->articleCrud->getById($id);
            $user = $this->getLoggedUser();
            if (!$item || $item->user->id !== $user->id) {
                $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
                $this->redirect('Article:default');
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $items = $this->articleCrud->getAllByUserForPage($this->page, 10, $this->getLoggedUser());
        $this->preparePaginator($items->count(), 10);
        $this->items = $items;
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
    }

    /**
     * @return Forms\ArticleForm
     */
    protected function createComponentForm()
    {
        return new Forms\ArticleForm(
            $this->translator,
            $this->tagCrud,
            $this->articleCrud,
            $this->getLoggedUser(),
            $this->item
        );
    }
}
