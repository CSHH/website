<?php

namespace AppTests\Presenters;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../../bootstrap.php';

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
