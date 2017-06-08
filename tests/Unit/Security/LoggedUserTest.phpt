<?php

namespace AppTests\Unit\Security;

use App\Entities\UserEntity;
use App\Security\LoggedUser;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class LoggedUserTest extends Tester\TestCase
{
    use UnitMocks;

    public function testGetLoggedUserEntityReturnsEntity()
    {
        $user = $this->user;
        $this->mock($user, 'isLoggedIn', 1, true);
        $this->mock($user, 'getId', 1, 1);

        $userRepository = $this->userRepository;
        $this->mock($userRepository, 'getById', 1, new UserEntity);

        $loggedUser = new LoggedUser($userRepository, $user);
        Assert::type('App\Entities\UserEntity', $loggedUser->getLoggedUserEntity());
    }

    public function testGetLoggedUserEntityReturnsNull()
    {
        $user = $this->user;
        $this->mock($user, 'isLoggedIn');

        $loggedUser = new LoggedUser($this->userRepository, $user);
        Assert::null($loggedUser->getLoggedUserEntity());
    }
}

$testCase = new LoggedUserTest;
$testCase->run();
