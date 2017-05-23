<?php

namespace AppTests\Unit\Security;

use App\Security\IdentityFactory;
use AppTests;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class IdentityFactoryTest extends Tester\TestCase
{
    public function testCreateIdentity()
    {
        $user                  = new AppTests\UserEntityImpl;
        $user->id              = 1;
        $user->username        = 'johndoe';
        $user->email           = 'johndoe@example.com';
        $user->forename        = 'John';
        $user->surname         = 'Doe';
        $user->isAuthenticated = true;

        $identityFactory = new IdentityFactory;

        $identity = $identityFactory->createIdentity($user);
        Assert::type('Nette\Security\Identity', $identity);
        Assert::same(1, $identity->getId());

        $data = $identity->getData();
        Assert::same('johndoe', $data['username']);
        Assert::same('johndoe@example.com', $data['email']);
        Assert::same('John', $data['forename']);
        Assert::same('Doe', $data['surname']);
        Assert::same(true, $data['isAuthenticated']);
    }
}

$testCase = new IdentityFactoryTest;
$testCase->run();
