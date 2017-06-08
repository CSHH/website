<?php

namespace App\Security;

use App\Entities\UserEntity;

class AccessChecker
{
    /** @var LoggedUser */
    private $loggedUser;

    public function __construct(LoggedUser $loggedUser)
    {
        $this->loggedUser = $loggedUser;
    }

    /**
     * @return bool
     */
    public function canAccess()
    {
        $user = $this->loggedUser->getLoggedUserEntity();

        return $user && $user->role > UserEntity::ROLE_USER;
    }
}
