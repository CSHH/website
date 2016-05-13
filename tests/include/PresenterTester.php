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
     * @param  string                $presenterName is fully qualified presenter name (module:module:presenter)
     * @param  string                $action
     * @param  string                $method
     * @param  array                 $params
     * @param  array                 $post
     * @return Application\IResponse
     */
    public function assertAppResponse($presenterName, $action, $method = 'GET', $params = array(), $post = array())
    {
        $presenter = $this->createPresenter($presenterName);

        $res = $this->processRequest($presenter, $presenterName, $action, $method, $params, $post);

        Assert::type('Nette\Application\Responses\TextResponse', $res);
        Assert::type('Nette\Bridges\ApplicationLatte\Template', $res->getSource());

        return $res;
    }

    /**
     * @param  string                $presenterName is fully qualified presenter name (module:module:presenter)
     * @param  string                $action
     * @param  string                $method
     * @param  array                 $params
     * @param  array                 $post
     * @return Application\IResponse
     */
    public function assertFormSubmitted($presenterName, $action, $method = 'GET', $params = array(), $post = array())
    {
        $presenter = $this->createPresenter($presenterName);

        $res = $this->processRequest($presenter, $presenterName, $action, $method, $params, $post);

        Assert::type('Nette\Application\Responses\RedirectResponse', $res);

        return $res;
    }

    /**
     * @return Application\IPresenterFactory
     */
    private function getPresenterFactory()
    {
        return $this->container->getByType('Nette\Application\IPresenterFactory');
    }

    /**
     * @param  string                 $presenterName
     * @return Application\IPresenter
     */
    private function createPresenter($presenterName)
    {
        $presenterFactory = $this->getPresenterFactory();

        $presenter = $presenterFactory->createPresenter($presenterName);
        $presenter->autoCanonicalize = false;

        return $presenter;
    }

    /**
     * @param  Application\IPresenter $presenter
     * @param  string                 $presenterName is fully qualified presenter name (module:module:presenter)
     * @param  string                 $action
     * @param  string                 $method
     * @param  array                  $params
     * @param  array                  $post
     * @return Application\IResponse
     */
    private function processRequest(Application\IPresenter $presenter, $presenterName, $action, $method, $params, $post)
    {
        $params['action'] = $action;

        $req = new Application\Request($presenterName, $method, $params, $post);

        return $presenter->run($req);
    }
}
