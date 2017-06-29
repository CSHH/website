<?php

namespace AppTests\Unit\Caching;

use App\Caching\MovieTagSectionCache;
use AppTests;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class MovieTagSectionCacheTest extends Tester\TestCase
{
    use UnitMocks;

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
        $this->mock($tagCache, 'getItems', 1, $tags);
        $this->mock($tagCache, 'getTagRepository', 1, $tagRepository);

        $movieTagSectionCache = new MovieTagSectionCache($tagCache, $this->movieRepository);

        $cachedTags = $movieTagSectionCache->getTags();
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

        $movieTagSectionCache = new MovieTagSectionCache($tagCache, $this->movieRepository);
        Assert::true($movieTagSectionCache->isTagInSection($tag));
    }

    public function testIsTagInSectionReturnsFalse()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'isTagInSection', 1, false);

        $movieTagSectionCache = new MovieTagSectionCache($tagCache, $this->movieRepository);
        Assert::false($movieTagSectionCache->isTagInSection(new AppTests\TagEntityImpl));
    }

    public function testDeleteSectionIfTagNotPresent()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'deleteSectionIfTagNotPresent');

        $movieTagSectionCache = new MovieTagSectionCache($tagCache, $this->movieRepository);
        Assert::null($movieTagSectionCache->deleteSectionIfTagNotPresent(new AppTests\TagEntityImpl));
    }

    public function testDeleteSection()
    {
        $tagCache = $this->tagCache;
        $this->mock($tagCache, 'deleteSection');

        $movieTagSectionCache = new MovieTagSectionCache($tagCache, $this->movieRepository);
        Assert::null($movieTagSectionCache->deleteSection());
    }
}

$testCase = new MovieTagSectionCacheTest;
$testCase->run();
