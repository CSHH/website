<?php

namespace AppTests\Presenters;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../../bootstrap.php';

class VideoPresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Video', 'default', 'GET');
    }
}

$testCase = new VideoPresenterTest($container);
$testCase->run();
