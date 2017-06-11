<?php

namespace App\Admin;

use App\Entities;
use App\Forms;

abstract class SharedContentDetailPresenter extends SecurePresenter
{
    /** @var Forms\WikiFormInterface @inject */
    public $wikiForm;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /** @var Entities\BaseEntity */
    private $item;

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
