<?php

namespace AppTests\Unit\Caching;

use App\Caching\TagCache;
use AppTests;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class TagCacheTest extends Tester\TestCase
{
    use UnitMocks;

    public function testSetArticleRepository()
    {
        $tagCache = new TagCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Caching\TagCache', $tagCache->setArticleRepository($this->articleRepository));
    }

    public function testSetImageRepository()
    {
        $tagCache = new TagCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Caching\TagCache', $tagCache->setImageRepository($this->imageRepository));
    }

    public function testSetVideoRepository()
    {
        $tagCache = new TagCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Caching\TagCache', $tagCache->setVideoRepository($this->videoRepository));
    }

    public function testSetWikiRepository()
    {
        $tagCache = new TagCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Caching\TagCache', $tagCache->setWikiRepository($this->wikiRepository));
    }

    public function testGetAll()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 6, []);

        $tagRepository = $this->tagRepository;
        $this->mock($tagRepository, 'getAll', 1, []);

        $tagCache = new TagCache($netteCache, $tagRepository);
        $tagCache->setArticleRepository($this->articleRepository);
        $tagCache->setImageRepository($this->imageRepository);
        $tagCache->setVideoRepository($this->videoRepository);
        $tagCache->setWikiRepository($this->wikiRepository);

        Assert::type('array', $tagCache->getAll());
    }

    public function testGetAllFromEmptyCache()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 6);
        $this->mock($netteCache, 'save', 6, $tag);

        $tagRepository = $this->tagRepository;
        $this->mock($tagRepository, 'getAll', 1, [$tag]);

        $tagCache = new TagCache($netteCache, $tagRepository);

        $article           = new AppTests\ArticleEntityImpl;
        $article->id       = 1;
        $articleRepository = $this->articleRepository;
        $this->mock($articleRepository, 'getAllByTag', 1, [$article]);
        $tagCache->setArticleRepository($articleRepository);

        $image           = new AppTests\ImageEntityImpl;
        $image->id       = 1;
        $imageRepository = $this->imageRepository;
        $this->mock($imageRepository, 'getAllByTag', 1, [$image]);
        $tagCache->setImageRepository($imageRepository);

        $video           = new AppTests\VideoEntityImpl;
        $video->id       = 1;
        $videoRepository = $this->videoRepository;
        $this->mock($videoRepository, 'getAllByTag', 1, [$video]);
        $tagCache->setVideoRepository($videoRepository);

        $wiki           = new AppTests\WikiEntityImpl;
        $wiki->id       = 1;
        $wikiRepository = $this->wikiRepository;
        $this->mock($wikiRepository, 'getAllByTag', 3, [$wiki]);
        $tagCache->setWikiRepository($wikiRepository);

        $result = $tagCache->getAll();
        Assert::type('array', $result);
        Assert::count(6, $result);
        Assert::type('array', $result[TagCache::SECTION_ARTICLES]);
        Assert::count(1, $result[TagCache::SECTION_ARTICLES]);
        Assert::type('array', $result[TagCache::SECTION_IMAGES]);
        Assert::count(1, $result[TagCache::SECTION_IMAGES]);
        Assert::type('array', $result[TagCache::SECTION_VIDEOS]);
        Assert::count(1, $result[TagCache::SECTION_VIDEOS]);
        Assert::type('array', $result[TagCache::SECTION_GAMES]);
        Assert::count(1, $result[TagCache::SECTION_GAMES]);
        Assert::type('array', $result[TagCache::SECTION_MOVIES]);
        Assert::count(1, $result[TagCache::SECTION_MOVIES]);
        Assert::type('array', $result[TagCache::SECTION_BOOKS]);
        Assert::count(1, $result[TagCache::SECTION_BOOKS]);
    }

    public function testDeleteSectionIfTagNotPresent()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, []);
        $this->mock($netteCache, 'remove');

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::null($tagCache->deleteSectionIfTagNotPresent(TagCache::SECTION_ARTICLES, $tag));
    }

    public function testIsTagInSectionReturnsTrue()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, [$tag->id => $tag]);

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::true($tagCache->isTagInSection(TagCache::SECTION_ARTICLES, $tag));
    }

    public function testIsTagInSectionReturnsFalse()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, []);

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::false($tagCache->isTagInSection(TagCache::SECTION_ARTICLES, new AppTests\TagEntityImpl));
    }

    public function testDeleteSection()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'remove');

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::null($tagCache->deleteSection(TagCache::SECTION_ARTICLES));
    }
}

$testCase = new TagCacheTest;
$testCase->run();
