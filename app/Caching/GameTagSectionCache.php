<?php

namespace App\Caching;

use App\Dao\WikiDao;
use App\Entities;

class GameTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 4;

    /** @var TagCache */
    private $tagCache;

    /** @var WikiDao */
    private $dataAccess;

    public function __construct(TagCache $tagCache, WikiDao $dataAccess)
    {
        $this->tagCache   = $tagCache;
        $this->dataAccess = $dataAccess;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->dataAccess,
            Entities\WikiEntity::TYPE_GAME
        );
    }

    /**
     * @param  Entities\TagEntity $tag
     * @return bool
     */
    public function isTagInSection(Entities\TagEntity $tag)
    {
        return $this->tagCache->isTagInSection(self::SECTION_ID, $tag);
    }

    /**
     * @param Entities\TagEntity $tag
     */
    public function deleteSectionIfTagNotPresent(Entities\TagEntity $tag)
    {
        $this->tagCache->deleteSectionIfTagNotPresent(self::SECTION_ID, $tag);
    }

    public function deleteSection()
    {
        $this->tagCache->deleteSection(self::SECTION_ID);
    }
}
