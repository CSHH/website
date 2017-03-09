<?php

namespace App\Security;

use App\Entities;
use App\Repositories;
use Nette;
use Nette\Localization\ITranslator;
use Nette\Security\Passwords;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator
{
    /** @var int Account activation limit set to one hour */
    const ACTIVATION_LIMIT_TIMESTAMP = 3600000;

    /** @var ITranslator */
    private $translator;

    /** @var Repositories\UserRepository */
    private $userRepository;

    /**
     * @param ITranslator   $translator
     * @param Repositories\UserRepository $userRepository
     */
    public function __construct(ITranslator $translator, Repositories\UserRepository $userRepository)
    {
        $this->translator = $translator;
        $this->userRepository   = $userRepository;
    }

    /**
     * @throws Nette\Security\AuthenticationException
     * @return Nette\Security\Identity
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        $user = $this->userRepository->getByEmail($email);

        if (!$user) {
            throw new Nette\Security\AuthenticationException(
                $this->translator->translate('locale.sign.incorrect_email'),
                self::IDENTITY_NOT_FOUND
            );
        } elseif (!$user->isAuthenticated) {
            throw new Nette\Security\AuthenticationException(
                $this->translator->translate('locale.sign.authentication_waiting'),
                self::NOT_APPROVED
            );
        } elseif (!Passwords::verify($password . $user->salt, $user->password)) {
            throw new Nette\Security\AuthenticationException(
                $this->translator->translate('locale.sign.incorrect_password'),
                self::INVALID_CREDENTIAL
            );
        } elseif (Passwords::needsRehash($user->password)) {
            $this->userRepository->updatePassword($user, $user->password);
        }

        return $this->updateIdentity($user);
    }

    /**
     * @param  Entities\UserEntity $user
     * @return Nette\Security\Identity
     */
    public function updateIdentity(Entities\UserEntity $user)
    {
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
