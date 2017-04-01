<?php

namespace AppTests\Unit\Repositories;

use App\Entities as AppEntities;
use App\Repositories as AppRepositories;
use AppTests;
use AppTests\UnitMocks;
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
    use UnitMocks;

    public function testCreate()
    {
        $query = $this->query;
        $this->mock($query, 'getSingleResult', 2, null);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select', 2);
        $this->mockAndReturnSelf($qb, 'from', 2);
        $this->mockAndReturnSelf($qb, 'join', 2);
        $this->mockAndReturnSelf($qb, 'where', 2);
        $this->mockAndReturnSelf($qb, 'setParameters', 2);
        $this->mock($qb, 'getQuery', 2, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 2, $qb);

        $em = $this->em;
        $this->mockAndReturnSelf($em, 'persist');
        $this->mockAndReturnSelf($em, 'flush');

        $repo = $this->getRepository($dao, $this->translator, $em);

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
        $query = $this->query;
        $this->mock($query, 'getSingleResult', 1, new AppEntities\ArticleEntity);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $translator = $this->translator;
        $this->mock($translator, 'translate', 1, '');

        $repo = $this->getRepository($dao, $translator, $this->em);

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
        $query = $this->query;
        $this->mock($query, 'getSingleResult', 1, null);
        $this->mock($query, 'getSingleResult', 1, new AppEntities\ArticleEntity);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select', 2);
        $this->mockAndReturnSelf($qb, 'from', 2);
        $this->mockAndReturnSelf($qb, 'join', 2);
        $this->mockAndReturnSelf($qb, 'where', 2);
        $this->mockAndReturnSelf($qb, 'setParameters', 2);
        $this->mock($qb, 'getQuery', 2, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 2, $qb);

        $translator = $this->translator;
        $this->mock($translator, 'translate', 1, '');

        $repo = $this->getRepository($dao, $translator, $this->em);

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
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllForPage($paginatorFactory, 1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllForPageActiveOnly()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllForPage($paginatorFactory, 1, 10, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllByTag()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, $this->getArticles());

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllByTag(new AppEntities\TagEntity);

        Assert::type('array', $result);
        Assert::count(5, $result);

        Assert::same(1, $result[0]->id);
        Assert::same('Article 1', $result[0]->name);
        Assert::same(2, $result[1]->id);
        Assert::same('Article 2', $result[1]->name);
        Assert::same(3, $result[2]->id);
        Assert::same('Article 3', $result[2]->name);
        Assert::same(4, $result[3]->id);
        Assert::same('Article 4', $result[3]->name);
        Assert::same(5, $result[4]->id);
        Assert::same('Article 5', $result[4]->name);
    }

    public function testGetByTagAndName()
    {
        $repo   = $this->prepareRepositoryForDetail();
        $result = $repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill');
        Assert::true($result instanceof AppEntities\ArticleEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    public function testGetByTagAndSlug()
    {
        $repo   = $this->prepareRepositoryForDetail();
        $result = $repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill');
        Assert::true($result instanceof AppEntities\ArticleEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    private function prepareRepositoryForDetail()
    {
        $article       = new AppTests\ArticleEntityImpl;
        $article->id   = 1;
        $article->name = 'Silent Hill';
        $article->slug = 'silent-hill';

        $query = $this->query;
        $this->mock($query, 'getSingleResult', 1, $article);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        return $this->getRepository($dao, $this->translator, $this->em);
    }

    public function testGetByTagAndNameAndThrowNonUniqueResultException()
    {
        $repo = $this->prepareRepositoryForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndNameAndThrowNoResultException()
    {
        $repo = $this->prepareRepositoryForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndSlugAndThrowNonUniqueResultException()
    {
        $repo = $this->prepareRepositoryForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill'));
    }

    public function testGetByTagAndSlugAndThrowNoResultException()
    {
        $repo = $this->prepareRepositoryForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill'));
    }

    private function prepareRepositoryForDetailToThrowException($class)
    {
        $query = $this->query;
        $query->shouldReceive('getSingleResult')
            ->once()
            ->andThrow($class);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        return $this->getRepository($dao, $this->translator, $this->em);
    }

    public function testGetAllByTagForPage()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'andWhere');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllByUserForPage()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllByUserForPage($paginatorFactory, 1, 10, new AppEntities\UserEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllInactiveForPage()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllInactiveForPage($paginatorFactory, 1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllInactiveByTagForPage()
    {
        $query         = $this->query;
        $arrayIterator = new \ArrayIterator($this->getArticles());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllInactiveByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $iterator = $result->getIterator();
        $item1    = $iterator->offsetGet(0);
        $item2    = $iterator->offsetGet(1);
        $item3    = $iterator->offsetGet(2);
        $item4    = $iterator->offsetGet(3);
        $item5    = $iterator->offsetGet(4);

        Assert::same(1, $item1->id);
        Assert::same('Article 1', $item1->name);
        Assert::same(2, $item2->id);
        Assert::same('Article 2', $item2->name);
        Assert::same(3, $item3->id);
        Assert::same('Article 3', $item3->name);
        Assert::same(4, $item4->id);
        Assert::same('Article 4', $item4->name);
        Assert::same(5, $item5->id);
        Assert::same('Article 5', $item5->name);
    }

    public function testGetAllNews()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, $this->getArticles());

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getAllNews();

        Assert::type('array', $result);
        Assert::count(5, $result);

        Assert::same(1, $result[0]->id);
        Assert::same('Article 1', $result[0]->name);
        Assert::same(2, $result[1]->id);
        Assert::same('Article 2', $result[1]->name);
        Assert::same(3, $result[2]->id);
        Assert::same('Article 3', $result[2]->name);
        Assert::same(4, $result[3]->id);
        Assert::same('Article 4', $result[3]->name);
        Assert::same(5, $result[4]->id);
        Assert::same('Article 5', $result[4]->name);
    }

    public function testGetLatestArticles()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, $this->getArticles());

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo   = $this->getRepository($dao, $this->translator, $this->em);
        $result = $repo->getLatestArticles();

        Assert::type('array', $result);
        Assert::count(5, $result);

        Assert::same(1, $result[0]->id);
        Assert::same('Article 1', $result[0]->name);
        Assert::same(2, $result[1]->id);
        Assert::same('Article 2', $result[1]->name);
        Assert::same(3, $result[2]->id);
        Assert::same('Article 3', $result[2]->name);
        Assert::same(4, $result[3]->id);
        Assert::same('Article 4', $result[3]->name);
        Assert::same(5, $result[4]->id);
        Assert::same('Article 5', $result[4]->name);
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

    private function getRepository($dao, $translator, $em)
    {
        $menuCache = $this->menuCache;
        $this->mockAndReturnSelf($menuCache, 'setArticleRepository');

        return new AppRepositories\ArticleRepository($dao, $translator, $em, $menuCache);
    }
}

$testCase = new ArticleRepositoryTest;
$testCase->run();
