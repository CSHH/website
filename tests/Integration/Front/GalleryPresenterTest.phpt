<?php

namespace AppTests\Integration\Front;

use AppTests\PresenterTester;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class GalleryPresenterTest extends Tester\TestCase
{
    use PresenterTester;

    public function testActionDefault()
    {
        $this->assertAppResponse('Front:Gallery', 'default', 'GET');
    }
}

$testCase = new GalleryPresenterTest($container);
$testCase->run();
