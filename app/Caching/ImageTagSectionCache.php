<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class ImageTagSectionCache implements TagSectionCacheInterface
{
    /** @var TagCache */
    private $tagCache;

    /** @var Repositories\ImageRepository */
    private $imageRepository;

    public function __construct(TagCache $tagCache)
    {
        $this->tagCache = $tagCache;
    }

    /**
     * @param Repositories\ImageRepository $imageRepository
     */
    public function setImageRepository(Repositories\ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            TagSectionCacheInterface::SECTION_IMAGES,
            $this->tagCache->getTagRepository()->getAll(),
            $this->imageRepository
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
