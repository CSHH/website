<?php

namespace AppTests\Model\Caching;

use App\Model\Caching\MenuCache;
use AppTests;
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

    public function testGetAll()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 6, array());

        $tagRepository = $this->tagRepository;
        $this->mock($tagRepository, 'getAll', 1, array());

        $menuCache = new MenuCache($netteCache, $tagRepository);
        $menuCache->setArticleRepository($this->articleRepository);
        $menuCache->setImageRepository($this->imageRepository);
        $menuCache->setVideoRepository($this->videoRepository);
        $menuCache->setWikiRepository($this->wikiRepository);

        Assert::type('array', $menuCache->getAll());
    }

    public function testGetAllFromEmptyCache()
    {
        $tag = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 6);
        $this->mock($netteCache, 'save', 6, $tag);

        $tagRepository = $this->tagRepository;
        $this->mock($tagRepository, 'getAll', 1, array($tag));

        $menuCache = new MenuCache($netteCache, $tagRepository);

        $article = new AppTests\ArticleEntityImpl;
        $article->id = 1;
        $articleRepository = $this->articleRepository;
        $this->mock($articleRepository, 'getAllByTag', 1, array($article));
        $menuCache->setArticleRepository($articleRepository);

        $image = new AppTests\ImageEntityImpl;
        $image->id = 1;
        $imageRepository = $this->imageRepository;
        $this->mock($imageRepository, 'getAllByTag', 1, array($image));
        $menuCache->setImageRepository($imageRepository);

        $video = new AppTests\VideoEntityImpl;
        $video->id = 1;
        $videoRepository = $this->videoRepository;
        $this->mock($videoRepository, 'getAllByTag', 1, array($video));
        $menuCache->setVideoRepository($videoRepository);

        $wiki = new AppTests\WikiEntityImpl;
        $wiki->id = 1;
        $wikiRepository = $this->wikiRepository;
        $this->mock($wikiRepository, 'getAllByTag', 3, array($wiki));
        $menuCache->setWikiRepository($wikiRepository);

        $result = $menuCache->getAll();
        Assert::type('array', $result);
        Assert::count(6, $result);
        Assert::type('array', $result[MenuCache::SECTION_ARTICLES]);
        Assert::count(1, $result[MenuCache::SECTION_ARTICLES]);
        Assert::type('array', $result[MenuCache::SECTION_IMAGES]);
        Assert::count(1, $result[MenuCache::SECTION_IMAGES]);
        Assert::type('array', $result[MenuCache::SECTION_VIDEOS]);
        Assert::count(1, $result[MenuCache::SECTION_VIDEOS]);
        Assert::type('array', $result[MenuCache::SECTION_GAMES]);
        Assert::count(1, $result[MenuCache::SECTION_GAMES]);
        Assert::type('array', $result[MenuCache::SECTION_MOVIES]);
        Assert::count(1, $result[MenuCache::SECTION_MOVIES]);
        Assert::type('array', $result[MenuCache::SECTION_BOOKS]);
        Assert::count(1, $result[MenuCache::SECTION_BOOKS]);
    }

    public function testDeleteSectionIfTagNotPresent()
    {
        $tag = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, array());
        $this->mock($netteCache, 'remove');

        $menuCache = new MenuCache($netteCache, $this->tagRepository);
        Assert::null($menuCache->deleteSectionIfTagNotPresent(MenuCache::SECTION_ARTICLES, $tag));
    }

    public function testIsTagInSectionReturnsTrue()
    {
        $tag = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, array($tag->id => $tag));

        $menuCache = new MenuCache($netteCache, $this->tagRepository);
        Assert::true($menuCache->isTagInSection(MenuCache::SECTION_ARTICLES, $tag));
    }

    public function testIsTagInSectionReturnsFalse()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, array());

        $menuCache = new MenuCache($netteCache, $this->tagRepository);
        Assert::false($menuCache->isTagInSection(MenuCache::SECTION_ARTICLES, new AppTests\TagEntityImpl));
    }

    public function testDeleteSection()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'remove');

        $menuCache = new MenuCache($netteCache, $this->tagRepository);
        Assert::null($menuCache->deleteSection(MenuCache::SECTION_ARTICLES));
    }
}

$testCase = new MenuCacheTest;
$testCase->run();
