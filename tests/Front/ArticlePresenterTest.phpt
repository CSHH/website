<?php

namespace AppTests\Front;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

class ArticlePresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Article', 'default', 'GET');
    }
}

$testCase = new ArticlePresenterTest($container);
$testCase->run();
