<?php

namespace AppTests\Unit\Emails;

use App\Emails\EmailMessageFactory;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class EmailMessageFactoryTest extends Tester\TestCase
{
    public function testCreateMessage()
    {
        Assert::type('Nette\Mail\Message', (new EmailMessageFactory)->createMessage());
    }
}

$testCase = new EmailMessageFactoryTest;
$testCase->run();
