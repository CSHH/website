<?php

namespace AppTests;

use App\RouterFactory;
use Nette\Application\Routers\RouteList;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap-unit.php';

class RouterFactoryTest extends Tester\TestCase
{
    public function testCreateRouter()
    {
        Assert::true(RouterFactory::createRouter() instanceof RouteList);
    }
}

$testCase = new RouterFactoryTest;
$testCase->run();
