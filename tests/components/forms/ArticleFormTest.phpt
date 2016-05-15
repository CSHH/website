<?php

namespace AppTests\Components\Forms;

use AppTests\Login;
use AppTests\PresenterTester;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';

class ArticleFormTest extends Tester\TestCase
{
    use Login;
    use PresenterTester;

	public function testSubmitForm()
    {
        $this->signIn($this->container);

        $articleRepository = $this->container->getByType('App\Model\Repositories\ArticleRepository');
        Assert::equal(5, $articleRepository->getCount());

        $post = array(
            'tagId' => 1,
            'name'  => 'Article XYZ',
            'perex' => 'Lorem ipsum dolor sit amet...',
            'text'  => 'Lorem ipsum dolor sit amet...',
            'do'    => 'form-form-submit',
        );

        $this->assertFormSubmitted('Admin:Article', 'form', 'POST', array(), $post);

        Assert::equal(6, $articleRepository->getCount());
    }
}

$testCase = new ArticleFormTest($container);
$testCase->run();
