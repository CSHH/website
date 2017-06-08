<?php

namespace AppTests\Unit\Security;

use App\Entities\UserEntity;
use App\Security\AccessChecker;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class AccessCheckerTest extends Tester\TestCase
{
    use UnitMocks;

    /**
     * @dataProvider getRoles
     *
     * @param int $role
     */
    public function testCanAccessReturnsTrue($role)
    {
        $ent       = new UserEntity;
        $ent->role = $role;

        $loggedUser = $this->loggedUser;
        $this->mock($loggedUser, 'getLoggedUserEntity', 1, $ent);

        $accessChecker = new AccessChecker($loggedUser);
        Assert::true($accessChecker->canAccess());
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [
            [UserEntity::ROLE_ADMINISTRATOR],
            [UserEntity::ROLE_MODERATOR],
        ];
    }

    public function testCanAccessReturnsFalse()
    {
        $ent       = new UserEntity;
        $ent->role = UserEntity::ROLE_USER;

        $loggedUser = $this->loggedUser;
        $this->mock($loggedUser, 'getLoggedUserEntity', 1, $ent);

        $accessChecker = new AccessChecker($loggedUser);
        Assert::false($accessChecker->canAccess());
    }
}

$testCase = new AccessCheckerTest;
$testCase->run();
