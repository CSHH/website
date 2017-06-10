<?php

namespace AppTests\Integration\Admin;

use AppTests\Login;
use AppTests\PresenterTester;
use Nelmio;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class GalleryPresenterTest extends Tester\TestCase
{
    use Login;
    use PresenterTester;

    public function testActionDefault()
    {
        $entityManager = $this->container->getByType('Kdyby\Doctrine\EntityManager');
        Nelmio\Alice\Fixtures::load(__DIR__ . '/fixtures.php', $entityManager);

        $this->signIn($this->container);

        $this->assertAppResponse('Admin:Gallery', 'default', 'GET');
    }
}

$testCase = new GalleryPresenterTest($container);
$testCase->run();
