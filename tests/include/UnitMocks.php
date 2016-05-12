<?php

namespace AppTests;

use Mockery;
use Mockery as m;

trait UnitMocks
{
    protected $articleRepository;
    protected $dao;
    protected $em;
    protected $qb;
    protected $query;
    protected $imageRepository;
    protected $menuCache;
    protected $netteCache;
    protected $paginator;
    protected $paginatorFactory;
    protected $tagRepository;
    protected $translator;
    protected $videoRepository;
    protected $wikiRepository;

    protected function getArticleRepositoryMock()
    {
        return m::mock('App\Model\Repositories\ArticleRepository');
    }

    protected function getEntityDaoMock()
    {
        return m::mock('Kdyby\Doctrine\EntityDao');
    }

    protected function getEntityManagerMock()
    {
        return m::mock('Kdyby\Doctrine\EntityManager');
    }

    protected function getQueryBuilderMock()
    {
        return m::mock('Kdyby\Doctrine\QueryBuilder');
    }

    protected function getQueryMock()
    {
        return m::mock('Doctrine\ORM\AbstractQuery');
    }

    protected function getImageRepositoryMock()
    {
        return m::mock('App\Model\Repositories\ImageRepository');
    }

    protected function getMenuCacheMock()
    {
        return m::mock('App\Model\Caching\MenuCache');
    }

    protected function getNetteCacheMock()
    {
        return m::mock('Nette\Caching\Cache');
    }

    protected function getPaginatorMock()
    {
        return m::mock('Doctrine\ORM\Tools\Pagination\Paginator');
    }

    protected function getPaginatorFactoryMock()
    {
        return m::mock('App\Model\Utils\PaginatorFactory');
    }

    protected function getTagRepositoryMock()
    {
        return m::mock('App\Model\Repositories\TagRepository');
    }

    protected function getTranslatorMock()
    {
        return m::mock('Nette\Localization\ITranslator');
    }

    protected function getVideoRepositoryMock()
    {
        return m::mock('App\Model\Repositories\VideoRepository');
    }

    protected function getWikiRepositoryMock()
    {
        return m::mock('App\Model\Repositories\WikiRepository');
    }

    /**
     * @param Mockery\MockInterface $mockObject
     * @param string                $methodName
     * @param string                $callTimes
     * @param string                $returnValue
     */
    protected function mock(Mockery\MockInterface $mockObject, $methodName, $callTimes = 1, $returnValue = null)
    {
        $mockObject->shouldReceive($methodName)
            ->times($callTimes)
            ->andReturn($returnValue);
    }

    /**
     * @param Mockery\MockInterface $mockObject
     * @param string                $methodName
     * @param string                $callTimes
     */
    protected function mockAndReturnSelf(Mockery\MockInterface $mockObject, $methodName, $callTimes = 1)
    {
        $mockObject->shouldReceive($methodName)
            ->times($callTimes)
            ->andReturnSelf();
    }

    protected function setUp()
    {
        $this->articleRepository = $this->getArticleRepositoryMock();
        $this->dao               = $this->getEntityDaoMock();
        $this->em                = $this->getEntityManagerMock();
        $this->qb                = $this->getQueryBuilderMock();
        $this->query             = $this->getQueryMock();
        $this->imageRepository   = $this->getImageRepositoryMock();
        $this->menuCache         = $this->getMenuCacheMock();
        $this->netteCache        = $this->getNetteCacheMock();
        $this->paginator         = $this->getPaginatorMock();
        $this->paginatorFactory  = $this->getPaginatorFactoryMock();
        $this->tagRepository     = $this->getTagRepositoryMock();
        $this->translator        = $this->getTranslatorMock();
        $this->videoRepository   = $this->getVideoRepositoryMock();
        $this->wikiRepository    = $this->getWikiRepositoryMock();
    }

    protected function tearDown()
    {
        m::close();
    }
}
