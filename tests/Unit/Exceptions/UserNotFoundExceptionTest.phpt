<?php

namespace AppTests\Unit\Exceptions;

use App\Exceptions\UserNotFoundException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class UserNotFoundExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new UserNotFoundException instanceof \Exception);
    }
}

$testCase = new UserNotFoundExceptionTest;
$testCase->run();
