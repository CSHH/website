<?php

namespace App\Presenters;

use Nette\Security\IUserStorage;

abstract class SecurePresenter extends PageablePresenter
{
    protected function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() === false) {
            if ($this->getUser()->getLogoutReason() === IUserStorage::INACTIVITY) {
                $this->flashMessage('Došlo k odhlášení z důvodu neaktivity.');
            }
            $this->redirect('Sign:in');
        }
    }
}
