<?php

namespace AppTests\Front;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

class MoviePresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Movie', 'default', 'GET');
    }
}

$testCase = new MoviePresenterTest($container);
$testCase->run();
