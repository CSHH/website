<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;
use App\Utils\PaginatorFactory;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /** @var PaginatorFactory @inject */
    public $paginatorFactory;

    /**
     * @param Repositories\BaseRepository $repository
     * @param string                      $redirect
     * @param int                         $id
     */
    protected function runActionForm(
        Repositories\BaseRepository $repository,
        $redirect,
        $id = null
    ) {
        if ($id !== null) {
            $item = $this->getItem($id, $repository);
            $user = $this->getLoggedUserEntity();
            if (!$item || $item->user->id !== $user->id) {
                $this->flashWithRedirect(
                    $this->translator->translate('locale.item.does_not_exist'),
                    $redirect
                );
            }

            $this->item = $item;
        }
    }

    /**
     * @param Repositories\BaseRepository $repository
     * @param int                         $limit
     * @param Entities\UserEntity         $user
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $limit, Entities\UserEntity $user)
    {
        $this->checkIfDisplayInactiveOnly();

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $repository->getAllInactiveForPage($this->paginatorFactory, $this->vp->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->paginatorFactory, $this->vp->page, $limit, $user);
        }

        $this->preparePaginator($this->items->count(), $limit);
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
}
