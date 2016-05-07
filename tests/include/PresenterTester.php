<?php

namespace AppTests;

use Nette\Application;
use Nette\DI\Container;
use Tester\Assert;

trait PresenterTester
{
    /** @var Container */
    private $container;

    /** @var Application\IPresenter */
    private $presenter;

    /** @var string */
    private $presenterName;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param  string $presenterName is fully qualified presenter name (module:module:presenter)
     * @param  string $action
     * @param  string $method
     * @param  array  $params
     * @param  array  $post
     * @return Application\IResponse
     */
    public function assertAppResponse($presenterName, $action, $method = 'GET', $params = array(), $post = array())
    {
        $presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');

        $presenter = $presenterFactory->createPresenter($presenterName);
        $presenter->autoCanonicalize = false;

        $params['action'] = $action;

        $req = new Application\Request($presenterName, $method, $params, $post);
        $res = $presenter->run($req);

        Assert::type('Nette\Application\Responses\TextResponse', $res);
        Assert::type('Nette\Bridges\ApplicationLatte\Template', $res->getSource());

        return $res;
    }
}
