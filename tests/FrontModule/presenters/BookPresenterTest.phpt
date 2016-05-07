<?php

namespace AppTests\Presenters;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../../bootstrap.php';

class BookPresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Book', 'default', 'GET');
    }
}

$testCase = new BookPresenterTest($container);
$testCase->run();
