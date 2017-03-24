<?php

namespace App\Admin;

use App\Forms;
use App\Repositories;

abstract class SharedContentPresenter extends PageablePresenter
{
    /** @var Forms\WikiFormInterface @inject */
    public $wikiForm;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

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
            $this->items = $this->wikiRepository->getAllWithDraftsForPage($this->vp->page, $limit, $type);
        } else {
            $this->items = $this->wikiRepository->getAllByUserForPage($this->vp->page, $limit, $this->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->items ? $this->items->count() : 0, $limit);
    }

    /**
     * @param  string         $type
     * @return Forms\WikiForm
     */
    protected function runCreateComponentWikiForm($type)
    {
        return $this->wikiForm->create(
            $this->getLoggedUserEntity(),
            $type,
            $this->item
        );
    }

    /**
     * @param  string              $type
     * @return Forms\WikiDraftForm
     */
    protected function runCreateComponentWikiDraftForm($type)
    {
        return $this->wikiDraftForm->create(
            $this->getLoggedUserEntity(),
            $type,
            $this->item
        );
    }
}
