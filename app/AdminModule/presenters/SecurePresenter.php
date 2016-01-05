<?php

namespace App\AdminModule\Presenters;

use App\Model\Crud;
use App\Model\Forms\ExtendingMethods as FormExtendingMethods;
use Nette\Security\IUserStorage;

abstract class SecurePresenter extends BasePresenter
{
    /** @var Crud\UserCrud @inject */
    public $userCrud;

    protected function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() === false) {
            if ($this->getUser()->getLogoutReason() === IUserStorage::INACTIVITY) {
                $this->flashMessage($this->translator->translate('locale.sign.session_expired'));
            }
            $this->redirect('Sign:in');
        }

        $ext = new FormExtendingMethods;
        $ext->registerMethods();
    }

    /**
     * @return Entities\UserEntity
     */
    protected function getLoggedUser()
    {
        return $this->userCrud->getById($this->getUser()->id);
    }
}
