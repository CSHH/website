<?php

namespace AppTests\Unit\Links;

use App\Links\AccountActivationLinkGenerator;
use App\Router\RouterFactory;
use Nette;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class AccountActivationLinkGeneratorTest extends Tester\TestCase
{
    public function testGenerateLink()
    {
        $router = RouterFactory::createRouter();
        $url = new Nette\Http\Url('http://localhost');
        $linkGenerator = new Nette\Application\LinkGenerator($router, $url);
        $link = new AccountActivationLinkGenerator($linkGenerator);
        Assert::same('http://localhost/aktivovat-ucet?usernameCanonical=johndoe&token=6hiib0jke9p7p7csh6xy3q0mm', $link->generateLink('johndoe', '6hiib0jke9p7p7csh6xy3q0mm'));
    }
}

$testCase = new AccountActivationLinkGeneratorTest;
$testCase->run();
