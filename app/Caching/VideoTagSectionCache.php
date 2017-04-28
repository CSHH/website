<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class VideoTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 3;

    /** @var TagCache */
    private $tagCache;

    /** @var Repositories\VideoRepository */
    private $videoRepository;

    public function __construct(TagCache $tagCache)
    {
        $this->tagCache = $tagCache;
    }

    /**
     * @param Repositories\VideoRepository $videoRepository
     */
    public function setVideoRepository(Repositories\VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    /**
     * @return Repositories\VideoRepository
     */
    public function getVideoRepository()
    {
        return $this->videoRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->videoRepository
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
