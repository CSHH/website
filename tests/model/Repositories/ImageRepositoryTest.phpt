<?php

namespace AppTests\Model\Repositories;

use AppTests\UnitMocks;
use App\Model\Entities as AppEntities;
use App\Model\Repositories as AppRepositories;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class ImageRepositoryTest extends Tester\TestCase
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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

        Assert::type('array', $repo->getAllByTag(new AppEntities\TagEntity));
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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

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

        $repo = $this->getRepository('', '', $dao, $dao, $this->em);

        Assert::true($repo->getAllInactiveByTagForPage($paginatorFactory, 1, 10, new AppEntities\TagEntity) instanceof Paginator);
    }

    private function getRepository($wwwDir, $uploadDir, $dao, $fileDao, $em)
    {
        return new AppRepositories\ImageRepository($wwwDir, $uploadDir, $dao, $fileDao, $em);
    }
}

$testCase = new ImageRepositoryTest;
$testCase->run();
