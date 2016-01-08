<?php

namespace AppTests\Presenters;

use AppTests\PresenterTester;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\ITemplate;
use Nette\DI\Container;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';

class ArticlePresenterTest extends Tester\TestCase
{
	/** @var PresenterTester */
	private $tester;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->tester = new PresenterTester($container);
	}

	protected function setUp()
	{
		$this->tester->init('Front:Article');
	}

    public function testActionDefault()
	{
		$res = $this->tester->test('default');
        Assert::true($res instanceof TextResponse);

        $src = $res->getSource();
        Assert::true($src instanceof ITemplate);
	}

    /*public function testActionDetail()
	{
		$res = $this->tester->test('detail', 'GET', array(
            'tagSlug' => 'povidky',
            'slug'    => 'neque-officia-quidem-non-excepturi-et-ratione',
        ));
        Assert::true($res instanceof TextResponse);

        $src = $res->getSource();
        Assert::true($src instanceof ITemplate);
	}*/
}

$testCase = new ArticlePresenterTest($container);
$testCase->run();
