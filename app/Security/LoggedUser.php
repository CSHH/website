<?php

namespace App\Security;

use App\Entities\UserEntity;
use App\Repositories\UserRepository;
use Nette\Security\User;

class LoggedUser
{
    /** @var UserRepository */
    private $userRepository;

    /** @var User */
    private $user;

    public function __construct(UserRepository $userRepository, User $user)
    {
        $this->userRepository = $userRepository;
        $this->user           = $user;
    }

    /**
     * @return UserEntity|null
     */
    public function getLoggedUserEntity()
    {
        return $this->user->isLoggedIn() ? $this->userRepository->getById($this->user->id) : null;
    }
}
