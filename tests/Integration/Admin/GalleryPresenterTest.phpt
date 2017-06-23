<?php

namespace AppTests\Integration\Admin;

use AppTests;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class GalleryPresenterTest extends Tester\TestCase
{
    use AppTests\Fixtures;
    use AppTests\Login;
    use AppTests\PresenterTester;

    public function testActionDefault()
    {
        $this->applyFixtures($this->container, __DIR__ . '/fixtures.php');
        $this->signIn($this->container);
        $this->assertAppResponse('Admin:Gallery', 'default', 'GET');
    }
}

$testCase = new GalleryPresenterTest($container);
$testCase->run();
