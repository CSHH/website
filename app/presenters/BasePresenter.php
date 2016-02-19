<?php

namespace App\Presenters;

use App\Model\Entities;
use App\Model\Repositories;
use Nette;
use Nette\Localization\ITranslator;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var ITranslator @inject */
    public $translator;

    /** @var Repositories\UserRepository @inject */
    public $userRepository;

    protected function throw404()
    {
        $this->error(
            $this->translator->translate('locale.error.page_not_found')
        );
    }

    /**
     * @return Entities\UserEntity|null
     */
    protected function getLoggedUserEntity()
    {
        $u = $this->getUser();

        return $u->isLoggedIn()
            ? $this->getItem($u->id, $this->userRepository)
            : null;
    }

    /**
     * @return bool
     */
    protected function canAccess()
    {
        $user = $this->getLoggedUserEntity();

        return $user && $user->role > Entities\UserEntity::ROLE_USER;
    }

    /**
     * @param string $message
     * @param string $redirect
     */
    protected function flashWithRedirect($message = '', $redirect = 'this')
    {
        $this->flashMessage($message);
        $this->redirect($redirect);
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
