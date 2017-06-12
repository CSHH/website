<?php

namespace AppTests\Unit\Emails;

use App\Emails\AccountActivationEmail;
use Mockery as m;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class AccountActivationEmailTest extends Tester\TestCase
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
        $url = m::mock('Nette\Http\Url');
        $url->shouldReceive('getHostUrl')->times(1)->andReturn('');
        $url->shouldReceive('getHost')->times(1)->andReturn('');
        $request = m::mock('Nette\Http\Request');
        $request->shouldReceive('getUrl')->times(1)->andReturn($url);
        $email = new AccountActivationEmail($translator, $mailer, $latteEngine, $message, $request, '/path/to/email.latte', 'sender@example.com', 'Subject');
        Assert::noError(function () use ($email) {
            $email->send('receiver@example.com', 'http://example.com');
        });
    }
}

$testCase = new AccountActivationEmailTest;
$testCase->run();
