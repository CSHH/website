<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\ActivationLimitExpiredException;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class ActivationLimitExpiredExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new ActivationLimitExpiredException instanceof \Exception);
    }
}

$testCase = new ActivationLimitExpiredExceptionTest;
$testCase->run();
