<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\WikiDraftConflictException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap-unit.php';

class WikiDraftConflictExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new WikiDraftConflictException instanceof \Exception);
    }
}

$testCase = new WikiDraftConflictExceptionTest;
$testCase->run();
