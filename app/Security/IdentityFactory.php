<?php

namespace App\Security;

use App\Entities;
use Nette;

class IdentityFactory
{
    /**
     * @param  Entities\UserEntity     $user
     * @return Nette\Security\Identity
     */
    public function createIdentity(Entities\UserEntity $user)
    {
        $data = [
            'username'        => $user->username,
            'email'           => $user->email,
            'forename'        => $user->forename,
            'surname'         => $user->surname,
            'role'            => $user->role,
            'isAuthenticated' => $user->isAuthenticated,
        ];

        return new Nette\Security\Identity($user->id, null, $data);
    }
}
