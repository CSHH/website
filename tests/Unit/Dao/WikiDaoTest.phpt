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
        $qb = $this->qb;
        $this->mockAndReturnSelf($qb, 'select');
        $this->mockAndReturnSelf($qb, 'from');
        $this->mockAndReturnSelf($qb, 'where');
        $this->mockAndReturnSelf($qb, 'setParameters');
        $this->mockAndReturnSelf($qb, 'orderBy');
        $this->mockAndReturnSelf($qb, 'setFirstResult');
        $this->mockAndReturnSelf($qb, 'setMaxResults');
        $this->mock($qb, 'getQuery', 1, $this->query);

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllForPage($dao, 1, 10, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllForPageActiveOnly()
    {
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllForPage($dao, 1, 10, AppEntities\WikiEntity::TYPE_GAME, true));
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('array', $wikiDao->getAllByTag($dao, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndName()
    {
        $wikiDao = $this->prepareWikiDaoForDetail();

        Assert::type('stdClass', $wikiDao->getByTagAndName($this->dao, new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndSlug()
    {
        $wikiDao = $this->prepareWikiDaoForDetail();

        Assert::type('stdClass', $wikiDao->getByTagAndSlug($this->dao, new AppEntities\TagEntity, 'silent-hill'));
    }

    private function prepareWikiDaoForDetail()
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

        return new WikiDao;
    }

    public function testGetByTagAndNameAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndName($this->dao, new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndNameAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndName($this->dao, new AppEntities\TagEntity, 'Silent Hill'));
    }

    public function testGetByTagAndSlugAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndSlug($this->dao, new AppEntities\TagEntity, 'silent-hill'));
    }

    public function testGetByTagAndSlugAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndSlug($this->dao, new AppEntities\TagEntity, 'silent-hill'));
    }

    private function prepareWikiDaoForDetailToThrowException($class)
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

        return new WikiDao;
    }

    public function testGetByTagAndNameAndType()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithType();

        Assert::type('stdClass', $wikiDao->getByTagAndNameAndType($this->dao, new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlugAndType()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithType();

        Assert::type('stdClass', $wikiDao->getByTagAndSlugAndType($this->dao, new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        return new WikiDao;
    }

    public function testGetByTagAndNameAndTypeAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndNameAndType($this->dao, new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndNameAndTypeAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndNameAndType($this->dao, new AppEntities\TagEntity, 'Silent Hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlugAndTypeAndThrowNonUniqueResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NonUniqueResultException');

        Assert::null($wikiDao->getByTagAndSlugAndType($this->dao, new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetByTagAndSlugAndTypeAndThrowNoResultException()
    {
        $wikiDao = $this->prepareWikiDaoForDetailWithTypeToThrowException('Doctrine\ORM\NoResultException');

        Assert::null($wikiDao->getByTagAndSlugAndType($this->dao, new AppEntities\TagEntity, 'silent-hill', AppEntities\WikiEntity::TYPE_GAME));
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        return new WikiDao;
    }

    public function testGetAllByTagForPage()
    {
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByTagForPage($this->dao, 1, 10, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME));
    }

    public function testGetAllByTagForPageActiveOnly()
    {
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByTagForPage($this->dao, 1, 10, new AppEntities\TagEntity, AppEntities\WikiEntity::TYPE_GAME, true));
    }

    public function testGetAllByUserForPage()
    {
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

        $dao = $this->dao;
        $this->mock($dao, 'createQueryBuilder', 1, $qb);

        $wikiDao = new WikiDao;

        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', $wikiDao->getAllByUserForPage($this->dao, 1, 10, new AppEntities\UserEntity, AppEntities\WikiEntity::TYPE_GAME));
    }
}

$testCase = new WikiDaoTest;
$testCase->run();
