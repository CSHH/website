<?php

namespace AppTests\Presenters;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../../bootstrap.php';

class GamePresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Game', 'default', 'GET');
    }
}

$testCase = new GamePresenterTest($container);
$testCase->run();
