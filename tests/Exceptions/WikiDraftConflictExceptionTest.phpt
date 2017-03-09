<?php

namespace AppTests\Exceptions;

use App\Exceptions\WikiDraftConflictException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

class WikiDraftConflictExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new WikiDraftConflictException instanceof \Exception);
    }
}

$testCase = new WikiDraftConflictExceptionTest;
$testCase->run();
