<?php

namespace AppTests\Unit\Security;

use App\Security\Authenticator;
use AppTests\UnitMocks;
use AppTests\UserEntityImpl;
use Nette;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class AuthenticatorTest extends Tester\TestCase
{
    use UnitMocks;

    public function testAuthenticate()
    {
        $user = new UserEntityImpl;
        $user->isAuthenticated = true;

        $userRepository = $this->userRepository;
        $this->mock($userRepository, 'getByEmail', 1, $user);
        $this->mock($userRepository, 'updatePassword');

        $passwords = $this->passwords;
        $this->mock($passwords, 'verify', 1, true);
        $this->mock($passwords, 'needsRehash', 1, true);

        $identityFactory = $this->identityFactory;
        $this->mock($identityFactory, 'createIdentity', 1, $this->identity);

        $authenticator = new Authenticator($identityFactory, $this->translator, $userRepository);
        Assert::type('Nette\Security\Identity', $authenticator->authenticate(['johndoe@example.com', 'secret']));
    }

    public function testAuthenticateFailsBecauseNoUserFound()
    {
        Assert::exception(
            function() {
                $translator = $this->translator;
                $this->mock($translator, 'translate', 1, 'locale.sign.authentication_issues');

                $userRepository = $this->userRepository;
                $this->mock($userRepository, 'getByEmail');

                $authenticator = new Authenticator($this->identityFactory, $translator, $userRepository);
                $authenticator->authenticate(['johndoe@example.com', 'secret']);
            },
            'Nette\Security\AuthenticationException',
            'locale.sign.authentication_issues',
            Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND
        );
    }

    public function testAuthenticateFailsBecauseUserIsNotAuthenticated()
    {
        Assert::exception(
            function() {
                $user = new UserEntityImpl;
                $user->isAuthenticated = false;

                $translator = $this->translator;
                $this->mock($translator, 'translate', 1, 'locale.sign.authentication_issues');

                $userRepository = $this->userRepository;
                $this->mock($userRepository, 'getByEmail', 1, $user);

                $authenticator = new Authenticator($this->identityFactory, $translator, $userRepository);
                $authenticator->authenticate(['johndoe@example.com', 'secret']);
            },
            'Nette\Security\AuthenticationException',
            'locale.sign.authentication_issues',
            Nette\Security\IAuthenticator::NOT_APPROVED
        );
    }

    public function testAuthenticateFailsBecauseOfIncorrectPassword()
    {
        Assert::exception(
            function() {
                $user = new UserEntityImpl;
                $user->isAuthenticated = true;

                $translator = $this->translator;
                $this->mock($translator, 'translate', 1, 'locale.sign.authentication_issues');

                $userRepository = $this->userRepository;
                $this->mock($userRepository, 'getByEmail', 1, $user);

                $passwords = $this->passwords;
                $this->mock($passwords, 'verify', 1, false);

                $authenticator = new Authenticator($this->identityFactory, $translator, $userRepository);
                $authenticator->authenticate(['johndoe@example.com', 'secret']);
            },
            'Nette\Security\AuthenticationException',
            'locale.sign.authentication_issues',
            Nette\Security\IAuthenticator::INVALID_CREDENTIAL
        );
    }
}

$testCase = new AuthenticatorTest;
$testCase->run();
