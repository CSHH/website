<?php

namespace AppTests\Unit\Links;

use App\Links\ForgottenPasswordLinkGenerator;
use App\Router\RouterFactory;
use Nette;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ForgottenPasswordLinkGeneratorTest extends Tester\TestCase
{
    public function testGenerateLink()
    {
        $router        = RouterFactory::createRouter();
        $url           = new Nette\Http\Url('http://localhost');
        $linkGenerator = new Nette\Application\LinkGenerator($router, $url);
        $link          = new ForgottenPasswordLinkGenerator($linkGenerator);
        Assert::same('http://localhost/zadat-nove-heslo?email=john.doe%40example.com&token=6hiib0jke9p7p7csh6xy3q0mm', $link->generateLink('john.doe@example.com', '6hiib0jke9p7p7csh6xy3q0mm'));
    }
}

$testCase = new ForgottenPasswordLinkGeneratorTest;
$testCase->run();
