<?php

namespace AppTests\Unit\Dao;

use App\Dao\SingleUserContentDao;
use App\Entities as AppEntities;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class SingleUserContentDaoTest extends Tester\TestCase
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllForPage($dao, 'MyEntity', $paginatorFactory, 1, 10));
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllForPage($dao, 'MyEntity', $paginatorFactory, 1, 10, true));
    }

    public function testGetAllByTag()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, []);

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

        $sucDao = new SingleUserContentDao;

        Assert::type('array', $sucDao->getAllByTag($dao, 'MyEntity', new AppEntities\TagEntity));
    }

    public function testGetByTagAndName()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetail();

        Assert::type('stdClass', $sucDao->getByTagAndName($this->dao, 'MyEntity', new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndSlug()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetail();

        Assert::type('stdClass', $sucDao->getByTagAndSlug($this->dao, 'MyEntity', new AppEntities\TagEntity, 'silent-hill'));
    }

    private function prepareSingleUserContentDaoForDetail()
    {
        $query = $this->query;
        $this->mock($query, 'getSingleResult', 1, new \stdClass);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        return new SingleUserContentDao;
    }

    public function testGetByTagAndNameAndThrowNonUniqueResultException()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($sucDao->getByTagAndName($this->dao, 'MyEntity', new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndNameAndThrowNoResultException()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($sucDao->getByTagAndName($this->dao, 'MyEntity', new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndSlugAndThrowNonUniqueResultException()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($sucDao->getByTagAndSlug($this->dao, 'MyEntity', new AppEntities\TagEntity, 'silent-hill'));
    }

    public function testGetByTagAndSlugAndThrowNoResultException()
    {
        $sucDao = $this->prepareSingleUserContentDaoForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($sucDao->getByTagAndSlug($this->dao, 'MyEntity', new AppEntities\TagEntity, 'silent-hill'));
    }

    private function prepareSingleUserContentDaoForDetailToThrowException($class)
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

        return new SingleUserContentDao;
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllByTagForPage($this->dao, 'MyEntity', $paginatorFactory, 1, 10, new AppEntities\TagEntity));
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllByTagForPage($this->dao, 'MyEntity', $paginatorFactory, 1, 10, new AppEntities\TagEntity, true));
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllByUserForPage($this->dao, 'MyEntity', $paginatorFactory, 1, 10, new AppEntities\UserEntity));
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllInactiveForPage($this->dao, 'MyEntity', $paginatorFactory, 1, 10));
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
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $sucDao = new SingleUserContentDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $sucDao->getAllInactiveByTagForPage($this->dao, 'MyEntity', $paginatorFactory, 1, 10, new AppEntities\TagEntity));
    }
}

$testCase = new SingleUserContentDaoTest;
$testCase->run();
