<?php

namespace AppTests;

use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\ITemplate;
use Nette\DI\Container;
use Tester;
use Tester\Assert;

class PresenterTester
{
    /** @var Container */
    private $container;

    /** @var IPresenter */
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
     * @param string $presenterName is fully qualified presenter name (module:module:presenter)
     */
    public function init($presenterName)
    {
        $presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');

        $this->presenter                   = $presenterFactory->createPresenter($presenterName);
        $this->presenter->autoCanonicalize = false;

        $this->presenterName = $presenterName;
    }

    /**
     * @param  string    $action
     * @param  string    $method
     * @param  array     $params
     * @param  array     $post
     * @return IResponse
     */
    public function test($action, $method = 'GET', $params = array(), $post = array())
    {
        $params['action'] = $action;

        $requset = new Request($this->presenterName, $method, $params, $post);

        return $this->presenter->run($requset);
    }

    /**
     * @param  string    $action
     * @param  string    $method
     * @param  array     $params
     * @param  array     $post
     * @return IResponse
     */
    public function testAction($action, $method = 'GET', $params = array(), $post = array())
    {
        $response = $this->test($action, $method, $params, $post);

        Assert::true($response instanceof TextResponse);

        $src = $response->getSource();
        Assert::true($src instanceof ITemplate);

        $html = (string) $src;
        $dom  = Tester\DomQuery::fromHtml($html);

        Assert::true($dom->has('title'));

        return $response;
    }
}
