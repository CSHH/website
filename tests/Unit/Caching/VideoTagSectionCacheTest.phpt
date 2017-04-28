<?php

namespace AppTests\Unit\Caching;

use App\Caching\VideoTagSectionCache;
use App\Caching\TagSectionCacheInterface;
use AppTests;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class VideoTagSectionCacheTest extends Tester\TestCase
{
    use UnitMocks;

    public function testSetAndGetVideoRepository()
    {
        $videoTagSectionCache = new VideoTagSectionCache($this->tagCache, $this->tagRepository);
        Assert::null($videoTagSectionCache->getVideoRepository());
        $videoTagSectionCache->setVideoRepository($this->videoRepository);
        Assert::type('App\Repositories\VideoRepository', $videoTagSectionCache->getVideoRepository());
    }

    public function testGetTags()
    {
        $tag1     = new AppTests\TagEntityImpl;
        $tag1->id = 1;
        $tag2     = new AppTests\TagEntityImpl;
        $tag2->id = 2;
        $tag3     = new AppTests\TagEntityImpl;
        $tag3->id = 3;
        $tags     = [$tag1, $tag2, $tag3];

        $tagRepository = $this->tagRepository;
        $this->mock($tagRepository, 'getAll', 1, $tags);

        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'getItemsForSection', 1, $tags);
        $this->mock($tagCache, 'getTagRepository', 1, $tagRepository);

        $videoTagSectionCache = new VideoTagSectionCache($tagCache, $tagRepository);
        $videoTagSectionCache->setVideoRepository($this->videoRepository);

        $cachedTags = $videoTagSectionCache->getTags();
        Assert::type('array', $cachedTags);
        Assert::count(3, $cachedTags);
        Assert::same(1, $cachedTags[0]->id);
        Assert::same(2, $cachedTags[1]->id);
        Assert::same(3, $cachedTags[2]->id);
    }

    public function testIsTagInSectionReturnsTrue()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'isTagInSection', 1, true);

        $videoTagSectionCache = new VideoTagSectionCache($tagCache);
        Assert::true($videoTagSectionCache->isTagInSection(TagSectionCacheInterface::SECTION_VIDEOS, $tag));
    }

    public function testIsTagInSectionReturnsFalse()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'isTagInSection', 1, false);

        $videoTagSectionCache = new VideoTagSectionCache($tagCache);
        Assert::false($videoTagSectionCache->isTagInSection(TagSectionCacheInterface::SECTION_VIDEOS, new AppTests\TagEntityImpl));
    }

    public function testDeleteSectionIfTagNotPresent()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'deleteSectionIfTagNotPresent');

        $videoTagSectionCache = new VideoTagSectionCache($tagCache);
        Assert::null($videoTagSectionCache->deleteSectionIfTagNotPresent(TagSectionCacheInterface::SECTION_VIDEOS, new AppTests\TagEntityImpl));
    }

    public function testDeleteSection()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'deleteSection');

        $videoTagSectionCache = new VideoTagSectionCache($tagCache);
        Assert::null($videoTagSectionCache->deleteSection(TagSectionCacheInterface::SECTION_VIDEOS));
    }
}

$testCase = new VideoTagSectionCacheTest;
$testCase->run();
