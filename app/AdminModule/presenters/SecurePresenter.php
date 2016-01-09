<?php

namespace App\AdminModule\Presenters;

use App\Model\Repositories;
use App\Model\Forms\ExtendingMethods as FormExtendingMethods;
use Nette\Security\IUserStorage;

abstract class SecurePresenter extends BasePresenter
{
    /** @var Repositories\UserRepository @inject */
    public $userRepository;

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
        return $this->userRepository->getById($this->getUser()->id);
    }
}
