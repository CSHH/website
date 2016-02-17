<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;

abstract class SingleUserContentPresenter extends PageablePresenter
{
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
     * @param  Repositories\BaseRepository $repository
     * @param  int                         $limit
     * @param  Entities\UserEntity         $user
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $limit, Entities\UserEntity $user)
    {
        $this->checkIfDisplayInactiveOnly();

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $repository->getAllInactiveForPage($this->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->page, $limit, $user);
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

    /**
     * @param  int                         $itemId
     * @param  Repositories\BaseRepository $repository
     * @return Entities\BaseEntity|null
     */
    protected function getItem($itemId, Repositories\BaseRepository $repository)
    {
        return $itemId ? $repository->getById($itemId) : null;
    }
}
