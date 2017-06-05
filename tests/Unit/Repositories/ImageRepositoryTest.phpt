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
class ImageRepositoryTest extends Tester\TestCase
{
    use AppTests\PaginatorToArrayConverter;
    use AppTests\UnitMocks;

    public function testGetAllForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllForPage(1, 10, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTag()
    {
        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTag', 1, $this->getImages());

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllByTag(new AppEntities\TagEntity);

        Assert::type('array', $result);
        Assert::count(5, $result);

        $this->assertResultItems($result);
    }

    public function testGetAllByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByTagForPageActiveOnly()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllByTagForPage(1, 10, new AppEntities\TagEntity, true);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllByUserForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllByUserForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllByUserForPage(1, 10, new AppEntities\UserEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllInactiveForPage(1, 10);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    public function testGetAllInactiveByTagForPage()
    {
        $arrayIterator = new \ArrayIterator($this->getImages());

        $paginator = $this->paginator;
        $this->mock($paginator, 'count', 1, $arrayIterator->count());
        $this->mock($paginator, 'getIterator', 1, $arrayIterator);

        $sucDao = $this->singleUserContentDao;
        $this->mock($sucDao, 'getAllInactiveByTagForPage', 1, $paginator);

        $repo   = $this->getRepository('', '', $this->dao, $this->dao, $sucDao, $this->em);
        $result = $repo->getAllInactiveByTagForPage(1, 10, new AppEntities\TagEntity);

        Assert::true($result instanceof Paginator);
        Assert::count(5, $result);

        $items = $this->paginatorToArray($result);

        $this->assertResultItems($items);
    }

    /**
     * @return array
     */
    private function getImages()
    {
        $images = [];
        for ($i = 0; $i < 5; $i++) {
            $id              = $i + 1;
            $image           = new AppTests\ImageEntityImpl;
            $image->id       = $id;
            $image->name     = "Image $id";
            $image->isActive = true;
            $images[]        = $image;
        }
        return $images;
    }

    private function assertResultItems(array $items)
    {
        Assert::same(1, $items[0]->id);
        Assert::same('Image 1', $items[0]->name);
        Assert::same(2, $items[1]->id);
        Assert::same('Image 2', $items[1]->name);
        Assert::same(3, $items[2]->id);
        Assert::same('Image 3', $items[2]->name);
        Assert::same(4, $items[3]->id);
        Assert::same('Image 4', $items[3]->name);
        Assert::same(5, $items[4]->id);
        Assert::same('Image 5', $items[4]->name);
    }

    private function getRepository($wwwDir, $uploadDir, $dao, $fileDao, $singleUserContentDao, $em)
    {
        return new AppRepositories\ImageRepository($wwwDir, $uploadDir, $dao, $fileDao, $singleUserContentDao, $em, $this->imageTagSectionCache);
    }
}

$testCase = new ImageRepositoryTest;
$testCase->run();
