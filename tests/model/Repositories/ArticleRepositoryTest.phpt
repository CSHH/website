<?php

namespace AppTests\Model\Repositories;

use App\Model\Repositories\ArticleRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mockery as m;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class ArticleRepositoryTest extends Tester\TestCase
{
    private $dao;
    private $translator;
    private $em;

    private function getEntityManagerMock()
    {
        return m::mock('Kdyby\Doctrine\EntityManager');
    }

    private function getQueryBuilderMock()
    {
        $query = new Query($this->getEntityManagerMock());

        $qb = m::mock('Kdyby\Doctrine\QueryBuilder');
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

        return $qb;
    }

    private function getEntityDaoMock()
    {
        $dao = m::mock('Kdyby\Doctrine\EntityDao');
        $dao->shouldReceive('createQueryBuilder')
            ->once()
            ->andReturn($this->getQueryBuilderMock());

        return $dao;
    }

    protected function getTranslatorMock()
    {
        return m::mock('Nette\Localization\ITranslator');
    }

    protected function setUp()
    {
        $this->dao        = $this->getEntityDaoMock();
        $this->translator = $this->getTranslatorMock();
        $this->em         = $this->getEntityManagerMock();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testGetAllForPage()
    {
        $repo = new ArticleRepository(
            $this->dao,
            $this->translator,
            $this->em
        );

        Assert::true($repo->getAllForPage(1, 10) instanceof Paginator);
    }
}

$testCase = new ArticleRepositoryTest;
$testCase->run();
