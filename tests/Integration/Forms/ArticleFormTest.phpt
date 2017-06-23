<?php

namespace AppTests\Integration\Forms;

use AppTests;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ArticleFormTest extends Tester\TestCase
{
    use AppTests\Fixtures;
    use AppTests\Login;
    use AppTests\PresenterTester;

    public function testSubmitFormCreate()
    {
        $this->applyFixtures($this->container, __DIR__ . '/ArticleFormTest.fixtures.php');
        $this->signIn($this->container);

        $articleRepository = $this->container->getByType('App\Repositories\ArticleRepository');
        Assert::equal(5, $articleRepository->getCount());

        $post = [
            'tagId' => 1,
            'name'  => 'Article XYZ',
            'perex' => 'Lorem ipsum dolor sit amet...',
            'text'  => 'Lorem ipsum dolor sit amet...',
            '_do'   => 'form-form-submit',
        ];

        $this->assertFormSubmitted('Admin:Article', 'form', 'POST', [], $post);

        Assert::equal(6, $articleRepository->getCount());
    }

    public function testSubmitFormUpdate()
    {
        $this->applyFixtures($this->container, __DIR__ . '/ArticleFormTest.fixtures.php');
        $this->signIn($this->container);

        $articleRepository = $this->container->getByType('App\Repositories\ArticleRepository');
        $ent1              = $articleRepository->getById(1);
        Assert::same('Article A', $ent1->name);

        $post = [
            'id'    => 1,
            'tagId' => 1,
            'name'  => 'Article XYZ',
            'perex' => 'Lorem ipsum dolor sit amet...',
            'text'  => 'Lorem ipsum dolor sit amet...',
            '_do'   => 'form-form-submit',
        ];

        $this->assertFormSubmitted('Admin:Article', 'form', 'POST', ['id' => 1], $post);

        $ent2 = $articleRepository->getById(1);
        Assert::same('Article XYZ', $ent2->name);
    }
}

$testCase = new ArticleFormTest($container);
$testCase->run();
