<?php

namespace App\Admin;

use App\Entities;
use App\Forms;
use App\Repositories;

abstract class SharedContentPresenter extends PageablePresenter
{
    /** @var Forms\WikiFormInterface @inject */
    public $wikiForm;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

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
            $user = $this->loggedUser->getLoggedUserEntity();
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
        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->filter->displayInactive()) {
            $this->items = $this->wikiRepository->getAllInactiveForPage($this->vp->page, $limit, $type);
        } elseif ($this->canAccess && $this->filter->displayDrafts()) {
            $this->items = $this->wikiRepository->getAllWithDraftsForPage($this->vp->page, $limit, $type);
        } else {
            $this->items = $this->wikiRepository->getAllByUserForPage($this->vp->page, $limit, $this->loggedUser->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->items ? $this->items->count() : 0, $limit);
    }

    /**
     * @param int                         $itemId
     * @param Repositories\BaseRepository $repository
     */
    protected function runHandleActivate($itemId, Repositories\BaseRepository $repository)
    {
        $item = $this->getItem($itemId, $repository);

        $this->checkItemAndFlashWithRedirectIfNull($item);

        $repository->activate($item);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param int                         $itemId
     * @param Repositories\BaseRepository $repository
     */
    protected function runHandleDelete($itemId, Repositories\BaseRepository $repository)
    {
        $item = $this->getItem($itemId, $repository);

        $this->checkItemAndFlashWithRedirectIfNull($item);

        $repository->delete($item);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'));
    }

    /**
     * @param Entities\BaseEntity $item
     * @param string              $redirect
     */
    protected function checkItemAndFlashWithRedirectIfNull(Entities\BaseEntity $item = null, $redirect = 'this')
    {
        if ($item === null) {
            $this->flashWithRedirect(
                $this->translator->translate('locale.item.does_not_exist'),
                $redirect
            );
        }
    }

    /**
     * @param  string         $type
     * @return Forms\WikiForm
     */
    protected function runCreateComponentWikiForm($type)
    {
        return $this->wikiForm->create(
            $this->loggedUser->getLoggedUserEntity(),
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
            $this->loggedUser->getLoggedUserEntity(),
            $type,
            $this->item
        );
    }
}
