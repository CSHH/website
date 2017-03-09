<?php

namespace AppTests\Exceptions;

use App\Exceptions\UserNotFoundException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

class UserNotFoundExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new UserNotFoundException instanceof \Exception);
    }
}

$testCase = new UserNotFoundExceptionTest;
$testCase->run();
