<?php

namespace AppTests\Unit\Dao;

use App\Dao\WikiDao;
use App\Entities as AppEntities;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class WikiDaoTest extends Tester\TestCase
{
    use UnitMocks;

    public function testGetAllForPage()
    {
        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllForPage(1, 10, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllForPageActiveOnly()
    {
        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'andWhere');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllForPage(1, 10, AppEntities\WikiEntity::TYPE_GAME, true));
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
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $this->paginatorFactory);

        Assert::type('array', $wikiDao->getAllByTag(new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndName()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithType();

        Assert::type('stdClass', $wikiDao->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlug()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithType();

        Assert::type('stdClass', $wikiDao->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    private function prepareWikiDaoForDetailWithType()
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

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        return new WikiDao($em, $this->paginatorFactory);
    }

    public function testGetByTagAndNameAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndNameAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlugAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlugAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    private function prepareWikiDaoForDetailWithTypeToThrowException($class)
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

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        return new WikiDao($em, $this->paginatorFactory);
    }

    public function testGetAllByTagForPage()
    {
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
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByTagForPage(1, 10, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllByTagForPageActiveOnly()
    {
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
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByTagForPage(1, 10, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME, true));
    }

    public function testGetAllByUserForPage()
    {
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
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByUserForPage(1, 10, new AppEntities\UserEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllActive()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, []);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $this->paginatorFactory);

        Assert::type('array', $wikiDao->getAllActive(AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllInactive()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, []);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $this->paginatorFactory);

        Assert::type('array', $wikiDao->getAllInactive(AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllInactiveForPage()
    {
        $paginatorFactory = $this->paginatorFactory;
        $this->mock($paginatorFactory, 'createPaginator', 1, $this->paginator);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllInactiveForPage(1, 10, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllInactiveByTagForPage()
    {
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
        $this->mock($qb, 'getQuery', 1, $this->query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $paginatorFactory);

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllInactiveByTagForPage(1, 10, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllTags()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, []);

        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'join');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'groupBy');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao($em, $this->paginatorFactory);

        Assert::type('array', $wikiDao->getAllTags(AppEntities\WikiEntity::TYPE_GAME));
    }
}

$testCase = new WikiDaoTest;
$testCase->run();
