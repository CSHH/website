<?php

namespace App\Admin;

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
            $this->redirect(':Front:Homepage:default');
        }

        $this->registerFormExtendingMethods();
    }
}
