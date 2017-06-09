<?php

namespace AppTests\Unit\Emails;

use App\Emails\MessageFactory;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class MessageFactoryTest extends Tester\TestCase
{
    public function testCreateMessage()
    {
        Assert::type('Nette\Mail\Message', (new MessageFactory)->createMessage());
    }
}

$testCase = new MessageFactoryTest;
$testCase->run();
