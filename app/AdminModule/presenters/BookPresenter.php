<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms\WikiForm;
use App\FrontModule\Components\Forms\WikiDraftForm;
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

    public function renderForm()
    {
        $this->template->item = $this->item;
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
     * @return WikiForm
     */
    protected function createComponentWikiForm()
    {
        return new WikiForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->wikiDraftRepository,
            $this->getLoggedUser(),
            Entities\WikiEntity::TYPE_BOOK,
            $this->item
        );
    }

    /**
     * @return WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return new WikiDraftForm(
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
