<?php

namespace AppTests\Exceptions;

use App\Exceptions\SpamEmailException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

/**
 * @testCase
 */
class SpamEmailExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new SpamEmailException instanceof \Exception);
    }
}

$testCase = new SpamEmailExceptionTest;
$testCase->run();