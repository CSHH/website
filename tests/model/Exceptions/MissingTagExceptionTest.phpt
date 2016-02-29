<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\MissingTagException;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class MissingTagExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new MissingTagException instanceof \Exception);
    }
}

$testCase = new MissingTagExceptionTest;
$testCase->run();
