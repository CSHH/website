<?php

namespace App\Security;

use App\Repositories;
use Nette;
use Nette\Localization\ITranslator;
use Nette\Security\Passwords;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator
{
    /** @var int Account activation limit set to one hour */
    const ACTIVATION_LIMIT_TIMESTAMP = 3600000;

    /** @var IdentityFactory */
    private $identityFactory;

    /** @var ITranslator */
    private $translator;

    /** @var Repositories\UserRepository */
    private $userRepository;

    /**
     * @param IdentityFactory             $identityFactory
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     */
    public function __construct(IdentityFactory $identityFactory, ITranslator $translator, Repositories\UserRepository $userRepository)
    {
        $this->identityFactory = $identityFactory;
        $this->translator      = $translator;
        $this->userRepository  = $userRepository;
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
            throw new Nette\Security\AuthenticationException($this->translator->translate('locale.sign.authentication_issues'), self::IDENTITY_NOT_FOUND);
        } elseif (!$user->isAuthenticated) {
            throw new Nette\Security\AuthenticationException($this->translator->translate('locale.sign.authentication_issues'), self::NOT_APPROVED);
        } elseif (!Passwords::verify($password . $user->salt, $user->password)) {
            throw new Nette\Security\AuthenticationException($this->translator->translate('locale.sign.authentication_issues'), self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($user->password)) {
            $this->userRepository->updatePassword($user, $user->password);
        }

        return $this->identityFactory->createIdentity($user);
    }
}
