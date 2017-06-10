<?php

namespace AppTests\Unit\Emails;

use App\Emails\ForgottenPasswordEmail;
use Tester;
use Tester\Assert;
use Mockery as m;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ForgottenPasswordEmailTest extends Tester\TestCase
{
    public function testSend()
    {
        $translator = m::mock('Nette\Localization\ITranslator');
        $translator->shouldReceive('translate')->times(1)->andReturn('');
        $mailer = m::mock('Nette\Mail\IMailer');
        $mailer->shouldReceive('send')->times(1)->andReturnNull();
        $latteEngine = m::mock('Latte\Engine');
        $latteEngine->shouldReceive('renderToString')->times(1)->andReturn('');
        $message = m::mock('Nette\Mail\Message');
        $message->shouldReceive('setFrom')->times(1)->andReturnSelf();
        $message->shouldReceive('addTo')->times(1)->andReturnSelf();
        $message->shouldReceive('setSubject')->times(1)->andReturnSelf();
        $message->shouldReceive('setHtmlBody')->times(1)->andReturnSelf();
        $email = new ForgottenPasswordEmail($translator, $mailer, $latteEngine, $message, '/path/to/email.latte', 'sender@example.com', 'Subject');
        Assert::noError(function() use ($email) {
            $email->send('receiver@example.com', 'http://example.com');
        });
    }
}

$testCase = new ForgottenPasswordEmailTest;
$testCase->run();
