<?php

namespace AppTests\Model\Repositories;

use AppTests\UnitMocks;
use App\Model\Entities as AppEntities;
use App\Model\Repositories as AppRepositories;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap-unit.php';

/**
 * @testCase
 */
class ArticleRepositoryTest extends Tester\TestCase
{
    use UnitMocks;

    public function testGetAllForPage()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllForPage($paginatorFactory, 1, 10) instanceof Paginator);
    }

    public function testGetAllForPageActiveOnly()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllForPage($paginatorFactory, 1, 10, true) instanceof Paginator);
    }

    public function testGetAllByTag()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, array());

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::type('array', $repo->getAllByTag(new AppEntities\TagEntity));
    }

    public function testGetByTagAndName()
    {
        $repo = $this->prepareRepositoryForDetail();

        Assert::true($repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill') instanceof AppEntities\ArticleEntity);
    }

    public function testGetByTagAndSlug()
    {
        $repo = $this->prepareRepositoryForDetail();

        Assert::true($repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill') instanceof AppEntities\ArticleEntity);
    }

    private function prepareRepositoryForDetail()
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
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity) instanceof Paginator);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'andWhere');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity, true) instanceof Paginator);
    }

    public function testGetAllByUserForPage()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllByUserForPage($paginatorFactory, 1, 10, new AppEntities\UserEntity) instanceof Paginator);
    }

    public function testGetAllInactiveForPage()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameter');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllInactiveForPage($paginatorFactory, 1, 10) instanceof Paginator);
    }

    public function testGetAllInactiveByTagForPage()
    {
        $query = $this->query;

        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::true($repo->getAllInactiveByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity) instanceof Paginator);
    }

    public function testGetAllNews()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, array());

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

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::type('array', $repo->getAllNews());
    }

    public function testGetLatestArticles()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, array());

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

        $repo = $this->getRepository($dao, $this->translator, $this->em);

        Assert::type('array', $repo->getLatestArticles());
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
