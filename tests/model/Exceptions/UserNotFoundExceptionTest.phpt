<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\UserNotFoundException;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class UserNotFoundExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new UserNotFoundException instanceof \Exception);
    }
}

$testCase = new UserNotFoundExceptionTest;
$testCase->run();
