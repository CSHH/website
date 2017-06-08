<?php

namespace AppTests;

use Mockery;
use Mockery as m;

trait UnitMocks
{
    protected $articleRepository;
    protected $articleTagSectionCache;
    protected $dao;
    protected $duplicityChecker;
    protected $em;
    protected $htmlPurifier;
    protected $identity;
    protected $identityFactory;
    protected $loggedUser;
    protected $qb;
    protected $query;
    protected $imageRepository;
    protected $imageTagSectionCache;
    protected $netteCache;
    protected $paginator;
    protected $paginatorFactory;
    protected $passwords;
    protected $singleUserContentDao;
    protected $tagCache;
    protected $tagRepository;
    protected $translator;
    protected $user;
    protected $userRepository;
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

    protected function getDuplicityCheckerMock()
    {
        return m::mock('App\Duplicities\DuplicityChecker');
    }

    protected function getEntityManagerMock()
    {
        return m::mock('Kdyby\Doctrine\EntityManager');
    }

    protected function getHtmlPurifierMock()
    {
        return m::mock('HTMLPurifier');
    }

    protected function getIdentityMock()
    {
        return m::mock('Nette\Security\Identity');
    }

    protected function getIdentityFactoryMock()
    {
        return m::mock('App\Security\IdentityFactory');
    }

    protected function getLoggedUserMock()
    {
        return m::mock('App\Security\LoggedUser');
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

    protected function getPasswordsMock()
    {
        return m::mock('alias:Nette\Security\Passwords');
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

    protected function getUserMock()
    {
        return m::mock('Nette\Security\User');
    }

    protected function getUserRepositoryMock()
    {
        return m::mock('App\Repositories\UserRepository');
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
        $this->duplicityChecker       = $this->getDuplicityCheckerMock();
        $this->em                     = $this->getEntityManagerMock();
        $this->htmlPurifier           = $this->getHtmlPurifierMock();
        $this->identity               = $this->getIdentityMock();
        $this->identityFactory        = $this->getIdentityFactoryMock();
        $this->loggedUser             = $this->getLoggedUserMock();
        $this->qb                     = $this->getQueryBuilderMock();
        $this->query                  = $this->getQueryMock();
        $this->imageRepository        = $this->getImageRepositoryMock();
        $this->imageTagSectionCache   = $this->getImageTagSectionCacheMock();
        $this->netteCache             = $this->getNetteCacheMock();
        $this->paginator              = $this->getPaginatorMock();
        $this->paginatorFactory       = $this->getPaginatorFactoryMock();
        $this->passwords              = $this->getPasswordsMock();
        $this->singleUserContentDao   = $this->getSingleUserContentDaoMock();
        $this->tagCache               = $this->getTagCacheMock();
        $this->tagRepository          = $this->getTagRepositoryMock();
        $this->translator             = $this->getTranslatorMock();
        $this->user                   = $this->getUserMock();
        $this->userRepository         = $this->getUserRepositoryMock();
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
