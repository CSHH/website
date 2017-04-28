<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class GameTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 4;

    /** @var TagCache */
    private $tagCache;

    /** @var Repositories\WikiRepository */
    private $wikiRepository;

    public function __construct(TagCache $tagCache)
    {
        $this->tagCache = $tagCache;
    }

    /**
     * @param Repositories\WikiRepository $wikiRepository
     */
    public function setWikiRepository(Repositories\WikiRepository $wikiRepository)
    {
        $this->wikiRepository = $wikiRepository;
    }

    /**
     * @return Repositories\WikiRepository
     */
    public function getWikiRepository()
    {
        return $this->wikiRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->wikiRepository,
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
