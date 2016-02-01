<?php

namespace AppTests\Presenters;

use App\FrontModule\Presenters\ArticlePresenter;
use Mockery as m;
use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class ArticlePresenterTest extends Tester\TestCase
{
    /** @var IPresenter */
    private $presenter;

	protected function setUp()
	{
        $urlScript = m::mock('Nette\Http\UrlScript');
        $urlScript->shouldReceive('getScriptPath')
            ->once()
            ->andReturn('');
        $urlScript->shouldReceive('isEqual')
            ->once()
            ->andReturn(true);

        $template = m::mock('Nette\Application\UI\ITemplate');
        $template->shouldReceive('getFile')
            ->once()
            ->andReturn(__DIR__ . '/../../../app/FrontModule/presenters/templates/Article/default.latte');
        $template->shouldReceive('setFile')
            ->once()
            ->andReturnNull();

        $parameters = array('uploadDir' => '');

        $context             = m::mock('Nette\DI\Container');
        $context->parameters = $parameters;
        $presenterFactory    = m::mock('Nette\Application\IPresenterFactory');
        $router              = m::mock('Nette\Application\IRouter');
        $router->shouldReceive('constructUrl')
            ->once()
            ->andReturn('');
        $httpRequest         = m::mock('Nette\Http\IRequest');
        $httpRequest->shouldReceive('isAjax')
            ->once()
            ->andReturn(false);
        $httpRequest->shouldReceive('getUrl')
            ->once()
            ->andReturn($urlScript);
        $httpRequest->shouldReceive('isMethod')
            ->once()
            ->andReturn(false);
        $httpResponse        = m::mock('Nette\Http\IResponse');
        $httpResponse->shouldReceive('isSent')
            ->once()
            ->andReturn(true);
        $session             = m::mock('Nette\Http\Session');
        $user                = m::mock('Nette\Security\User');
        $user->shouldReceive('isLoggedIn')
            ->once()
            ->andReturn(false);
        $templateFactory     = m::mock('Nette\Application\UI\ITemplateFactory');
        $templateFactory->shouldReceive('createTemplate')
            ->once()
            ->andReturn($template);

        $p = new ArticlePresenter;
        $p->injectPrimary(
            $context,
            $presenterFactory,
            $router,
            $httpRequest,
            $httpResponse,
            $session,
            $user,
            $templateFactory
        );

        $paginator = m::mock('Doctrine\ORM\Tools\Pagination\Paginator');
        $paginator->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $articleRepository = m::mock('App\Model\Repositories\ArticleRepository');
        $articleRepository->shouldReceive('getAllForPage')
            ->once()
            ->andReturn($paginator)
            ->getMock();

        $p->articleRepository = $articleRepository;

        $this->presenter = $p;
	}

    public function testActionDefault()
	{
        $params = array();

        $params['action'] = 'default';

        $request = new Request('Front:Article', 'GET', $params, array());

        $res = $this->presenter->run($request);
        Assert::true($res instanceof TextResponse);
	}
}

$testCase = new ArticlePresenterTest;
$testCase->run();
