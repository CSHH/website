<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class BookPresenter extends SharedContentPresenter
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
            $item = $this->wikiRepository->getById($id);
            $user = $this->getLoggedUser();
            if (!$item || $item->type !== Entities\WikiEntity::TYPE_BOOK || $item->createdBy->id !== $user->id) {
                $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
                $this->redirect('Book:default');
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $items = $this->wikiRepository->getAllByUserForPage($this->page, 10, $this->getLoggedUser(), Entities\WikiEntity::TYPE_BOOK);
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
            $this->tagRepository,
            $this->wikiRepository,
            $this->wikiDraftRepository,
            $this->getLoggedUser(),
            Entities\WikiEntity::TYPE_BOOK,
            $this->item
        );
    }
}
