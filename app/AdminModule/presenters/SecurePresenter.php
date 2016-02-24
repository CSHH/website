<?php

namespace App\AdminModule\Presenters;

use Nette\Security\IUserStorage;

abstract class SecurePresenter extends BasePresenter
{
    protected function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() === false) {
            if ($this->getUser()->getLogoutReason() === IUserStorage::INACTIVITY) {
                $this->flashMessage($this->translator->translate('locale.sign.session_expired'));
            }
            $this->redirect('Sign:in');
        }

        $this->registerFormExtendingMethods();
    }
}
