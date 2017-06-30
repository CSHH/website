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

    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /**
     * @param Repositories\WikiRepository $repository
     * @param string                      $type
     * @param string                      $redirect
     * @param int                         $id
     */
    protected function runActionForm(Repositories\WikiRepository $repository, $type, $redirect, $id = null)
    {
        if ($id !== null) {
            $item = $this->getItem($id, $repository);
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
     * @param Repositories\WikiRepository $repository
     * @param int                         $limit
     * @param string                      $type
     */
    protected function runActionDefault(Repositories\WikiRepository $repository, $limit, $type)
    {
        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->filter->displayInactive()) {
            $this->items = $repository->getAllInactiveForPage($this->vp->page, $limit, $type);
        } elseif ($this->canAccess && $this->filter->displayDrafts()) {
            $this->items = $repository->getAllWithDraftsForPage($this->vp->page, $limit, $type);
        } else {
            $this->items = $repository->getAllByUserForPage($this->vp->page, $limit, $this->loggedUser->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->items ? $this->items->count() : 0, $limit);
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param int                         $itemId
     */
    protected function runHandleActivate(Repositories\WikiRepository $repository, $itemId)
    {
        $item = $this->getItem($itemId, $repository);

        $this->checkItemAndFlashWithRedirectIfNull($item);

        $repository->activate($item);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param int                         $itemId
     */
    protected function runHandleDelete(Repositories\WikiRepository $repository, $itemId)
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
     * @param  Repositories\WikiRepository $repository
     * @param  string                      $type
     * @return Forms\WikiForm
     */
    protected function runCreateComponentWikiForm(Repositories\WikiRepository $repository, $type)
    {
        return $this->wikiForm->create(
            $repository,
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
