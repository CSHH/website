<?php

namespace AppTests\Model\Exceptions;

use App\Model\Exceptions\FormSentBySpamException;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap-unit.php';

class FormSentBySpamExceptionTest extends Tester\TestCase
{
    public function testInstanceOfParent()
    {
        Assert::true(new FormSentBySpamException instanceof \Exception);
    }
}

$testCase = new FormSentBySpamExceptionTest;
$testCase->run();
