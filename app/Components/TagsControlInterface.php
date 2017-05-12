<?php

namespace App\Components;

use App\Caching\TagSectionCacheInterface;

interface TagsControlInterface
{
    /**
     * @param  TagSectionCacheInterface $cache
     * @return TagsControl
     */
    public function create(TagSectionCacheInterface $cache);
}
