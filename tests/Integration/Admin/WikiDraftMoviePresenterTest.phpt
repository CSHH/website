<?php

namespace AppTests\Integration\Admin;

use AppTests;
use Tester;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class WikiDraftMoviePresenterTest extends Tester\TestCase
{
    use AppTests\Fixtures;
    use AppTests\Login;
    use AppTests\PresenterTester;

    public function testActionDefault()
    {
        $this->applyFixtures($this->container, __DIR__ . '/wiki_draft.fixtures.php');
        $this->signIn($this->container);
        $this->assertAppResponse('Admin:WikiDraftMovie', 'default', 'GET', ['wikiId' => 1]);
    }
}

$testCase = new WikiDraftMoviePresenterTest($container);
$testCase->run();
