<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\InvalidVideoUrlException;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class InvalidVideoUrlExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new InvalidVideoUrlException instanceof \InvalidArgumentException);
    }
}

$testCase = new InvalidVideoUrlExceptionTest;
$testCase->run();
