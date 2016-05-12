<?php

namespace AppTests\Model\Caching;

use App\Model\Caching\MenuCache;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap-unit.php';

class MenuCacheTest extends Tester\TestCase
{
    use UnitMocks;

    public function testSetArticleRepository()
    {
        $menuCache = new MenuCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Model\Caching\MenuCache', $menuCache->setArticleRepository($this->articleRepository));
    }

    public function testSetImageRepository()
    {
        $menuCache = new MenuCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Model\Caching\MenuCache', $menuCache->setImageRepository($this->imageRepository));
    }

    public function testSetVideoRepository()
    {
        $menuCache = new MenuCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Model\Caching\MenuCache', $menuCache->setVideoRepository($this->videoRepository));
    }

    public function testSetWikiRepository()
    {
        $menuCache = new MenuCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Model\Caching\MenuCache', $menuCache->setWikiRepository($this->wikiRepository));
    }
}

$testCase = new MenuCacheTest;
$testCase->run();
