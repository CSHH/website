<?php

namespace App\Model\Security;

use App\Model\Crud;
use Nette;
use Nette\Security\Passwords;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator
{
    /** @var int Account activation limit set to one hour */
    const ACTIVATION_LIMIT_TIMESTAMP = 3600000;

    /** @var Crud\UserCrud */
    private $userCrud;

    /**
     * @param Crud\UserCrud $userCrud
     */
    public function __construct(Crud\UserCrud $userCrud)
    {
        $this->userCrud = $userCrud;
    }

    /**
     * @throws Nette\Security\AuthenticationException
     * @return Nette\Security\Identity
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        $user = $this->userCrud->getByEmail($email);

        if (!$user) {
            throw new Nette\Security\AuthenticationException(
                'Nesprávný přihlašovací e-mail.',
                self::IDENTITY_NOT_FOUND
            );
        } elseif (!$user->isAuthenticated) {
            throw new Nette\Security\AuthenticationException(
                'Čeká se na autentizaci.',
                self::NOT_APPROVED
            );
        } elseif (!Passwords::verify($password . $user->salt, $user->password)) {
            throw new Nette\Security\AuthenticationException(
                'Nesprávné heslo.',
                self::INVALID_CREDENTIAL
            );
        } elseif (Passwords::needsRehash($user->password)) {
            $this->userCrud->updatePassword($user, $user->password);
        }

        $data = array(
            'username'        => $user->username,
            'email'           => $user->email,
            'forename'        => $user->forename,
            'surname'         => $user->surname,
            'role'            => $user->role,
            'isAuthenticated' => $user->isAuthenticated,
        );

        return new Nette\Security\Identity($user->id, null, $data);
    }
}
