<?php

namespace AppTests\Unit\Exceptions;

use App\Exceptions\ActivationLimitExpiredException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ActivationLimitExpiredExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new ActivationLimitExpiredException instanceof \Exception);
    }
}

$testCase = new ActivationLimitExpiredExceptionTest;
$testCase->run();
