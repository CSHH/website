<?php

namespace AppTests\Model\Repositories;

use App\Model\Entities as AppEntities;
use App\Model\Repositories as AppRepositories;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mockery as m;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class ImageRepositoryTest extends Tester\TestCase
{
    private $dao;
    private $em;
    private $qb;
    private $query;

    private function getEntityDaoMock()
    {
        return m::mock('Kdyby\Doctrine\EntityDao');
    }

    private function getEntityManagerMock()
    {
        return m::mock('Kdyby\Doctrine\EntityManager');
    }

    private function getQueryBuilderMock()
    {
        return m::mock('Kdyby\Doctrine\QueryBuilder');
    }

    private function getQueryMock()
    {
        return m::mock('Doctrine\ORM\AbstractQuery');
    }

    protected function setUp()
    {
        $this->dao   = $this->getEntityDaoMock();
        $this->em    = $this->getEntityManagerMock();
        $this->qb    = $this->getQueryBuilderMock();
        $this->query = $this->getQueryMock();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testGetAllForPage()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllForPage(1, 10) instanceof Paginator);
    }

    public function testGetAllForPageActiveOnly()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameter')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllForPage(1, 10, true) instanceof Paginator);
    }

    public function testGetAllByTag()
    {
        $query = $this->query;
        $query->shouldReceive('getResult')
            ->once()
            ->andReturn(array());

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('join')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameter')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::type('array', $repo->getAllByTag(new AppEntities\TagEntity));
    }

    public function testGetAllByTagForPage()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('join')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameters')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity) instanceof Paginator);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('join')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('andWhere')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameters')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity, true) instanceof Paginator);
    }

    public function testGetAllByUserForPage()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('join')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameter')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllByUserForPage(1, 10, new AppEntities\UserEntity) instanceof Paginator);
    }

    public function testGetAllInactiveForPage()
    {
        $query = $this->query;

        $qb = $this->qb;
        $qb->shouldReceive('select')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('from')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('where')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setParameter')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setFirstResult')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('setMaxResults')
            ->once()
            ->andReturnSelf();
        $qb->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        $dao = $this->dao;
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($qb);

        $repo = new AppRepositories\ImageRepository(
            'wwwDirMock',
            'uploadDirMock',
            $dao,
            $dao,
            $this->em
        );

        Assert::true($repo->getAllInactiveForPage(1, 10) instanceof Paginator);
    }
}

$testCase = new ImageRepositoryTest;
$testCase->run();
