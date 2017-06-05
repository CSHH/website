<?php

namespace AppTests\Unit\Repositories;

use App\Entities as AppEntities;
use App\Repositories as AppRepositories;
use AppTests;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class VideoRepositoryTest extends Tester\TestCase
{
    use AppTests\PaginatorToArrayConverter;
    use AppTests\UnitMocks;

    public function testGetAllForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllForPage(1, 10, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTag()
    {
        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTag', 1, $this->getVideos());

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllByTag(new AppEntities\TagEntity);

        Assert::type('array', $result);
        Assert::count(5, $result);

        $this->assertResultItems($result);
    }

    public function testGetByTagAndName()
    {
        $repo   = $this->prepareRepositoryForDetail('getByTagAndName');
        $result = $repo->getByTagAndName(new AppEntities\TagEntity, 'Silent Hill');

        Assert::true($result instanceof AppEntities\VideoEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    public function testGetByTagAndSlug()
    {
        $repo   = $this->prepareRepositoryForDetail('getByTagAndSlug');
        $result = $repo->getByTagAndSlug(new AppEntities\TagEntity, 'silent-hill');

        Assert::true($result instanceof AppEntities\VideoEntity);
        Assert::same(1, $result->id);
        Assert::same('Silent Hill', $result->name);
        Assert::same('silent-hill', $result->slug);
    }

    /**
     * @param  string                          $singleUserContentDaoMockMethod
     * @return AppRepositories\VideoRepository
     */
    private function prepareRepositoryForDetail($singleUserContentDaoMockMethod)
    {
        $video       = new AppTests\VideoEntityImpl;
        $video->id   = 1;
        $video->name = 'Silent Hill';
        $video->slug = 'silent-hill';

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, $singleUserContentDaoMockMethod, 1, $video);

        return $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
    }

    public function testGetAllByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByUserForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByUserForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllByUserForPage(1, 10, new AppEntities\UserEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllInactiveForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getVideos());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', $this->dao, $sucDao, $this->translator, $this->em);
        $result = $repo->getAllInactiveByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    /**
     * @return array
     */
    private function getVideos()
    {
        $videos = [];
        for ($i = 0; $i < 5; $i++) {
            $id              = $i + 1;
            $video           = new AppTests\VideoEntityImpl;
            $video->id       = $id;
            $video->name     = "Video $id";
            $video->isActive = true;
            $videos[]        = $video;
        }
        return $videos;
    }

    private function assertResultItems(array $items)
    {
        Assert::same(1, $items[0]->id);
        Assert::same('Video 1', $items[0]->name);
        Assert::same(2, $items[1]->id);
        Assert::same('Video 2', $items[1]->name);
        Assert::same(3, $items[2]->id);
        Assert::same('Video 3', $items[2]->name);
        Assert::same(4, $items[3]->id);
        Assert::same('Video 4', $items[3]->name);
        Assert::same(5, $items[4]->id);
        Assert::same('Video 5', $items[4]->name);
    }

    private function getRepository($vimeoOembedEndpoint, $dao, $singleUserContentDao, $translator, $em)
    {
        return new AppRepositories\VideoRepository($vimeoOembedEndpoint, $dao, $singleUserContentDao, $translator, $em, $this->videoTagSectionCache);
    }
}

$testCase = new VideoRepositoryTest;
$testCase->run();
