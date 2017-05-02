<?php

namespace App\Caching;

use App\Entities;

interface TagSectionCacheInterface
{
    /**
     * @return array
     */
    public function getTags();

    /**
     * @param  Entities\TagEntity $tag
     * @return bool
     */
    public function isTagInSection(Entities\TagEntity $tag);

    /**
     * @param Entities\TagEntity $tag
     */
    public function deleteSectionIfTagNotPresent(Entities\TagEntity $tag);

    public function deleteSection();
}
