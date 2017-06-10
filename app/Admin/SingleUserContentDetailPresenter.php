<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

abstract class SingleUserContentDetailPresenter extends SecurePresenter
{
    /** @var Entities\BaseEntity */
    protected $item;

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
            $user = $this->loggedUser->getLoggedUserEntity();
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
     * @param int                         $itemId
     * @param Repositories\BaseRepository $repository
     * @param string                      $redirect
     */
    protected function runHandleActivate($itemId, Repositories\BaseRepository $repository, $redirect)
    {
        $item = $this->getItem($itemId, $repository);

        $this->checkItemAndFlashWithRedirectIfNull($item);

        $repository->activate($item);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'), $redirect);
    }

    /**
     * @param int                         $itemId
     * @param Repositories\BaseRepository $repository
     * @param string                      $redirect
     */
    protected function runHandleDelete($itemId, Repositories\BaseRepository $repository, $redirect)
    {
        $item = $this->getItem($itemId, $repository);

        $this->checkItemAndFlashWithRedirectIfNull($item);

        $repository->delete($item);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'), $redirect);
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
