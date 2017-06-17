<?php

namespace App\Admin;

use App\Entities;
use App\Forms;
use App\Repositories;

abstract class SharedContentDetailPresenter extends SecurePresenter
{
    /** @var Forms\WikiFormInterface @inject */
    public $wikiForm;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var Entities\BaseEntity */
    protected $item;

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
