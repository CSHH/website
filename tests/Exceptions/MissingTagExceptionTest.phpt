<?php

namespace AppTests\Exceptions;

use App\Exceptions\MissingTagException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

class MissingTagExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new MissingTagException instanceof \Exception);
    }
}

$testCase = new MissingTagExceptionTest;
$testCase->run();
