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
        Assert::same('http://localhost/aktivovat-ucet?email=john.doe%40example.com&token=6hiib0jke9p7p7csh6xy3q0mm', $link->generateLink('john.doe@example.com', '6hiib0jke9p7p7csh6xy3q0mm'));
    }
}

$testCase = new AccountActivationLinkGeneratorTest;
$testCase->run();
