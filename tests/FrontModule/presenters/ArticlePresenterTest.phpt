<?php

namespace AppTests\Presenters;

use Nette\Application\Request;
use Nette\DI\Container;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';

class ArticlePresenterTest extends Tester\TestCase
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function setUp()
    {
        $source = __DIR__ . '/../../db-image.sqlite';
        $target = $this->container->getParameters()['testingsqlitedb'];
        \Nette\Utils\FileSystem::copy($source, $target);
    }

    public function testActionDefault()
    {
        $presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');

        $presenter = $presenterFactory->createPresenter('Front:Article');
        $presenter->autoCanonicalize = false;

        $req = new Request('Front:Article', 'GET', array('action' => 'default'));
        $res = $presenter->run($req);

        Assert::type('Nette\Application\Responses\TextResponse', $res);
        Assert::type('Nette\Bridges\ApplicationLatte\Template', $res->getSource());
    }
}

$testCase = new ArticlePresenterTest($container);
$testCase->run();
