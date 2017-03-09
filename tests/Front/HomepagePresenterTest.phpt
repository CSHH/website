<?php

namespace AppTests\Front;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class HomepagePresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Homepage', 'default', 'GET');
    }
}

$testCase = new HomepagePresenterTest($container);
$testCase->run();
