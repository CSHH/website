<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\FrontModule\Components\Forms\WikiDraftForm;
use App\Model\Repositories;

abstract class SharedContentPresenter extends PageablePresenter
{
    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /**
     * @param string $type
     * @param string $redirect
     * @param int    $id
     */
    protected function runActionForm($type, $redirect, $id = null)
    {
        if ($id !== null) {
            $item = $this->getItem($id, $this->wikiRepository);
            $user = $this->getLoggedUserEntity();
            if (!$item || $item->type !== $type || $item->createdBy->id !== $user->id) {
                $this->flashWithRedirect(
                    $this->translator->translate('locale.item.does_not_exist'),
                    $redirect
                );
            }

            $this->item = $item;
        }
    }

    public function renderForm()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int    $limit
     * @param string $type
     */
    protected function runActionDefault($limit, $type)
    {
        $this->checkIfDisplayInactiveOnly();

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $this->wikiRepository->getAllWithDraftsForPage($this->page, $limit, $type);
        } else {
            $this->items = $this->wikiRepository->getAllByUserForPage($this->page, $limit, $this->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->items ? $this->items->count() : 0, $limit);
    }

    /**
     * @param  string $type
     * @return Forms\WikiForm
     */
    protected function runCreateComponentWikiForm($type)
    {
        return new Forms\WikiForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->getLoggedUserEntity(),
            $type,
            $this->item
        );
    }

    /**
     * @param  string $type
     * @return Forms\WikiDraftForm
     */
    protected function runCreateComponentWikiDraftForm($type)
    {
        return new WikiDraftForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->wikiDraftRepository,
            $this->getLoggedUserEntity(),
            $type,
            $this->item
        );
    }
}
