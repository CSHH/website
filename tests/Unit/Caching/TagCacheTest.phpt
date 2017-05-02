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

    public function testGetTagRepository()
    {
        $tagCache = new TagCache($this->netteCache, $this->tagRepository);
        Assert::type('App\Repositories\TagRepository', $tagCache->getTagRepository());
    }

    public function testIsTagInSectionReturnsTrue()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, [$tag->id => $tag]);

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::true($tagCache->isTagInSection(1, $tag));
    }

    public function testIsTagInSectionReturnsFalse()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, []);

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::false($tagCache->isTagInSection(1, new AppTests\TagEntityImpl));
    }

    public function testDeleteSectionIfTagNotPresent()
    {
        $tag     = new AppTests\TagEntityImpl;
        $tag->id = 1;

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load', 1, []);
        $this->mock($netteCache, 'remove');

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::null($tagCache->deleteSectionIfTagNotPresent(1, $tag));
    }

    public function testDeleteSection()
    {
        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'remove');

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        Assert::null($tagCache->deleteSection(1));
    }
}

$testCase = new TagCacheTest;
$testCase->run();
