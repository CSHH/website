<?php

namespace AppTests\Unit\Repositories;

use App\Entities as AppEntities;
use App\Repositories as AppRepositories;
use AppTests;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nette\Utils\ArrayHash;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ArticleRepositoryTest extends Tester\TestCase
{
    use AppTests\PaginatorToArrayConverter;
    use AppTests\UnitMocks;

    public function testCreate()
    {
        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getByTagAndName', 1);
        $this->mock($sucDao, 'getByTagAndSlug', 1);

        $em = $this->em;
        $this->mockAndReturnSelf($em, 'persist');
        $this->mockAndReturnSelf($em, 'flush');

        $htmlPurifier = $this->htmlPurifier;
        $this->mock($htmlPurifier, 'purify', 1, 'Text');

        $repo = $this->getRepository($this->dao, $sucDao, $this->translator, $em, $htmlPurifier);

        $values        = new ArrayHash;
        $values->name  = 'Silent Hill';
        $values->perex = 'Perex';
        $values->text  = 'Text';

        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $user     = new AppTests\UserEntityImpl;
        $user->id = 1;

        $result = $repo->create($values, $tag, $user, new AppEntities\ArticleEntity);

        Assert::type('App\Entities\TagEntity', $result->tag);
        Assert::same(1, $result->tag->id);
        Assert::type('App\Entities\UserEntity', $result->user);
        Assert::same(1, $result->user->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
        Assert::same('Perex', $result->perex);
        Assert::same('Text', $result->text);
        Assert::false($result->isActive);
    }

    public function testCreatePossibleUniqueKeyDuplicationExceptionInCombinationOfTagAndName()
    {
        $entity       = new AppTests\TagEntityImpl;
        $entity->name = 'Silent Hill';

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getByTagAndName', 1, $entity);

        $translator = $this->translator;
        $this->mock($translator, 'translate', 1, '');

        $repo = $this->getRepository($this->dao, $sucDao, $translator, $this->em, $this->htmlPurifier);

        $values        = new ArrayHash;
        $values->name  = 'Silent Hill';
        $values->perex = 'Perex';
        $values->text  = 'Text';

        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $user     = new AppTests\UserEntityImpl;
        $user->id = 1;

        $ent = new AppEntities\ArticleEntity;
        Assert::exception(function () use ($repo, $values, $tag, $user, $ent) {
            $repo->create($values, $tag, $user, $ent);
        }, 'App\Duplicities\PossibleUniqueKeyDuplicationException');
        Assert::null($ent->slug);
    }

    public function testCreatePossibleUniqueKeyDuplicationExceptionInCombinationOfTagAndSlug()
    {
        $entity       = new AppTests\TagEntityImpl;
        $entity->slug = 'silent-hill';

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getByTagAndName', 1);
        $this->mock($sucDao, 'getByTagAndSlug', 1, $entity);

        $translator = $this->translator;
        $this->mock($translator, 'translate', 1, '');

        $repo = $this->getRepository($this->dao, $sucDao, $translator, $this->em, $this->htmlPurifier);

        $values        = new ArrayHash;
        $values->name  = 'Silent Hill';
        $values->perex = 'Perex';
        $values->text  = 'Text';

        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $user     = new AppTests\UserEntityImpl;
        $user->id = 1;

        $ent = new AppEntities\ArticleEntity;
        Assert::exception(function () use ($repo, $values, $tag, $user, $ent) {
            $repo->create($values, $tag, $user, $ent);
        }, 'App\Duplicities\PossibleUniqueKeyDuplicationException');
        Assert::same('silent-hill', $ent->slug);
    }

    public function testGetAllForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllForPage(1, 10, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTag()
    {
        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTag', 1, $this->getArticles());

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllByTag(new AppEntities\TagEntity);

        Assert::type('array', $result);
        Assert::count(5, $result);

        $this->assertResultItems($result);
    }

    public function testGetByTagAndName()
    {
        $repo   = $this->prepareRepositoryForDetail('getByTagAndName');
        $result = $repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill');

        Assert::true($result instanceof AppEntities\ArticleEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    public function testGetByTagAndSlug()
    {
        $repo   = $this->prepareRepositoryForDetail('getByTagAndSlug');
        $result = $repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill');

        Assert::true($result instanceof AppEntities\ArticleEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    /**
     * @param  string                            $singleUserContentDaoMockMethod
     * @return AppRepositories\ArticleRepository
     */
    private function prepareRepositoryForDetail($singleUserContentDaoMockMethod)
    {
        $article       = new AppTests\ArticleEntityImpl;
        $article->id   = 1;
        $article->name = 'Silent Hill';
        $article->slug = 'silent-hill';

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, $singleUserContentDaoMockMethod, 1, $article);

        return $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
    }

    public function testGetAllByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByUserForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByUserForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllByUserForPage(1, 10, new AppEntities\UserEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllInactiveForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveByTagForPage', 1, $paginator);

        $repo   = $this->getRepository($this->dao, $sucDao, $this->translator, $this->em, $this->htmlPurifier);
        $result = $repo->getAllInactiveByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    /**
     * @return array
     */
    private function getArticles()
    {
        $articles = [];
        for ($i = 0; $i < 5; $i++) {
            $id                = $i + 1;
            $article           = new AppTests\ArticleEntityImpl;
            $article->id       = $id;
            $article->name     = "Article $id";
            $article->isActive = true;
            $articles[]        = $article;
        }
        return $articles;
    }

    private function assertResultItems(array $items)
    {
        Assert::same(1, $items[0]->id);
        Assert::same('Article 1', $items[0]->name);
        Assert::same(2, $items[1]->id);
        Assert::same('Article 2', $items[1]->name);
        Assert::same(3, $items[2]->id);
        Assert::same('Article 3', $items[2]->name);
        Assert::same(4, $items[3]->id);
        Assert::same('Article 4', $items[3]->name);
        Assert::same(5, $items[4]->id);
        Assert::same('Article 5', $items[4]->name);
    }

    private function getRepository($dao, $singleUserContentDao, $translator, $em, $htmlPurifier)
    {
        return new AppRepositories\ArticleRepository($dao, $singleUserContentDao, $translator, $em, $this->articleTagSectionCache, $htmlPurifier);
    }
}

$testCase = new ArticleRepositoryTest;
$testCase->run();
