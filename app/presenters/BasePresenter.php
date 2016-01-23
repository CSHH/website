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
    protected function getLoggedUser()
    {
        $u = $this->getUser();

        return $u->loggedIn ? $this->userRepository->getById($u->id) : null;
    }
}
