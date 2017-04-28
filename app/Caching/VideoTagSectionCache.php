<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class VideoTagSectionCache implements TagSectionCacheInterface
{
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
            TagSectionCacheInterface::SECTION_VIDEOS,
            $this->tagCache->getTagRepository()->getAll(),
            $this->videoRepository
        );
    }

    /**
     * @param  int                $section
     * @param  Entities\TagEntity $tag
     * @return bool
     */
    public function isTagInSection($section, Entities\TagEntity $tag)
    {
        return $this->tagCache->isTagInSection($section, $tag);
    }

    /**
     * @param int                $section
     * @param Entities\TagEntity $tag
     */
    public function deleteSectionIfTagNotPresent($section, Entities\TagEntity $tag)
    {
        $this->tagCache->deleteSectionIfTagNotPresent($section, $tag);
    }

    /**
     * @param int $section
     */
    public function deleteSection($section)
    {
        $this->tagCache->deleteSection($section);
    }
}
