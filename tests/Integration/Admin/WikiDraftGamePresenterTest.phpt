<?php

namespace AppTests\Integration\Admin;

use AppTests;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class WikiDraftGamePresenterTest extends Tester\TestCase
{
    use AppTests\Fixtures;
    use AppTests\Login;
    use AppTests\PresenterTester;

    public function testActionDefault()
    {
        $this->applyFixtures($this->container, __DIR__ . '/wiki_draft.fixtures.php');
        $this->signIn($this->container);
        $this->assertAppResponse('Admin:WikiDraftGame', 'default', 'GET', ['wikiId' => 1]);
    }
}

$testCase = new WikiDraftGamePresenterTest($container);
$testCase->run();
