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
class VideoListingPresenterTest extends Tester\TestCase
{
    use Login;
    use PresenterTester;

    public function testActionDefault()
    {
        $entityManager = $this->container->getByType('Kdyby\Doctrine\EntityManager');
        Nelmio\Alice\Fixtures::load(__DIR__ . '/fixtures.php', $entityManager);

        $this->signIn($this->container);

        $this->assertAppResponse('Admin:VideoListing', 'default', 'GET');
    }
}

$testCase = new VideoListingPresenterTest($container);
$testCase->run();
