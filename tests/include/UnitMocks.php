<?php

namespace AppTests;

use Mockery;
use Mockery as m;

trait UnitMocks
{
    protected $articleRepository;
    protected $articleTagSectionCache;
    protected $dao;
    protected $em;
    protected $qb;
    protected $query;
    protected $imageRepository;
    protected $imageTagSectionCache;
    protected $netteCache;
    protected $paginator;
    protected $paginatorFactory;
    protected $singleUserContentDao;
    protected $tagCache;
    protected $tagRepository;
    protected $translator;
    protected $videoRepository;
    protected $videoTagSectionCache;
    protected $wikiDao;
    protected $wikiRepository;

    protected function getArticleRepositoryMock()
    {
        return m::mock('App\Repositories\ArticleRepository');
    }

    protected function getArticleTagSectionCacheMock()
    {
        return m::mock('App\Caching\ArticleTagSectionCache');
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
        return m::mock('App\Repositories\ImageRepository');
    }

    protected function getImageTagSectionCacheMock()
    {
        return m::mock('App\Caching\ImageTagSectionCache');
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
        return m::mock('App\Utils\PaginatorFactory');
    }

    protected function getSingleUserContentDaoMock()
    {
        return m::mock('App\Dao\SingleUserContentDao');
    }

    protected function getTagCacheMock()
    {
        return m::mock('App\Caching\TagCache');
    }

    protected function getTagRepositoryMock()
    {
        return m::mock('App\Repositories\TagRepository');
    }

    protected function getTranslatorMock()
    {
        return m::mock('Nette\Localization\ITranslator');
    }

    protected function getVideoRepositoryMock()
    {
        return m::mock('App\Repositories\VideoRepository');
    }

    protected function getVideoTagSectionCacheMock()
    {
        return m::mock('App\Caching\VideoTagSectionCache');
    }

    protected function getWikiDaoMock()
    {
        return m::mock('App\Dao\WikiDao');
    }

    protected function getWikiRepositoryMock()
    {
        return m::mock('App\Repositories\WikiRepository');
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
        $this->articleRepository      = $this->getArticleRepositoryMock();
        $this->articleTagSectionCache = $this->getArticleTagSectionCacheMock();
        $this->dao                    = $this->getEntityDaoMock();
        $this->em                     = $this->getEntityManagerMock();
        $this->qb                     = $this->getQueryBuilderMock();
        $this->query                  = $this->getQueryMock();
        $this->imageRepository        = $this->getImageRepositoryMock();
        $this->imageTagSectionCache   = $this->getImageTagSectionCacheMock();
        $this->netteCache             = $this->getNetteCacheMock();
        $this->paginator              = $this->getPaginatorMock();
        $this->paginatorFactory       = $this->getPaginatorFactoryMock();
        $this->singleUserContentDao   = $this->getSingleUserContentDaoMock();
        $this->tagCache               = $this->getTagCacheMock();
        $this->tagRepository          = $this->getTagRepositoryMock();
        $this->translator             = $this->getTranslatorMock();
        $this->videoRepository        = $this->getVideoRepositoryMock();
        $this->videoTagSectionCache   = $this->getVideoTagSectionCacheMock();
        $this->wikiDao                = $this->getWikiDaoMock();
        $this->wikiRepository         = $this->getWikiRepositoryMock();
    }

    protected function tearDown()
    {
        m::close();
    }
}
