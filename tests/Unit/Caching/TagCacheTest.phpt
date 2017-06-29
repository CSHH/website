<?php

namespace AppTests\Unit\Caching;

use App\Caching\TagCache;
use AppTests;
use AppTests\UnitMocks;
use Mockery as m;
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

    public function testGetItems()
    {
        $tag1     = new \AppTests\TagEntityImpl;
        $tag1->id = 1;
        $tag2     = new \AppTests\TagEntityImpl;
        $tag2->id = 2;
        $tag3     = new \AppTests\TagEntityImpl;
        $tag3->id = 3;
        $tags     = [$tag1, $tag2, $tag3];

        $netteCache = $this->netteCache;
        $this->mock($netteCache, 'load');
        $this->mock($netteCache, 'save', 1, $tags);

        $repository = m::mock('App\Repositories\BaseRepository');
        $repository->shouldReceive('getAllByTag')
            ->times(3)
            ->andReturn([new \stdClass]);

        $tagCache = new TagCache($netteCache, $this->tagRepository);
        $items    = $tagCache->getItems(1, $tags, $repository);

        Assert::type('array', $items);
        Assert::count(3, $items);
        Assert::type('App\Entities\TagEntity', $items[1]);
        Assert::same(1, $items[1]->id);
        Assert::type('App\Entities\TagEntity', $items[2]);
        Assert::same(2, $items[2]->id);
        Assert::type('App\Entities\TagEntity', $items[3]);
        Assert::same(3, $items[3]->id);
    }
}

$testCase = new TagCacheTest;
$testCase->run();
