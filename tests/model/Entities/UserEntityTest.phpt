<?php

namespace AppTests\Model\Entities;

use App\Model\Entities\UserEntity;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

/**
 * @testCase
 */
class UserEntityTest extends Tester\TestCase
{
    public function testSetUsername()
    {
        $ent = new UserEntity;
        $ent->setUsername('johndoe');
        Assert::same('johndoe', $ent->getUsername());
    }

    public function testSetEmail()
    {
        $ent = new UserEntity;
        $ent->setEmail('john.doe@example.com');
        Assert::same('john.doe@example.com', $ent->getEmail());
    }

    public function testSetPassword()
    {
        $ent = new UserEntity;
        $ent->setPassword('000000000000000000000000000000000000000000000000000000000000');
        Assert::same('000000000000000000000000000000000000000000000000000000000000', $ent->getPassword());
    }

    public function testSetSalt()
    {
        $ent = new UserEntity;
        $ent->setSalt('0000000000');
        Assert::same('0000000000', $ent->getSalt());
    }

    public function testSetToken()
    {
        $ent = new UserEntity;
        $ent->setToken('0000000000');
        Assert::same('0000000000', $ent->getToken());
    }

    public function testSetTokenCreatedAt()
    {
        $ent = new UserEntity;
        $ent->setTokenCreatedAt(new \DateTime('1970-01-01 00:00:00'));
        Assert::type('DateTime', $ent->getTokenCreatedAt());
        Assert::same('1970-01-01 00:00:00', $ent->getTokenCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testSetIsAuthenticated()
    {
        $ent = new UserEntity;
        $ent->setIsAuthenticated(true);
        Assert::true($ent->getIsAuthenticated());
    }
}

$testCase = new UserEntityTest;
$testCase->run();
