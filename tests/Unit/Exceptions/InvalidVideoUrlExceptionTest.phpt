<?php

namespace AppTests\Unit\Exceptions;

use App\Exceptions\InvalidVideoUrlException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class InvalidVideoUrlExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new InvalidVideoUrlException instanceof \InvalidArgumentException);
    }
}

$testCase = new InvalidVideoUrlExceptionTest;
$testCase->run();
