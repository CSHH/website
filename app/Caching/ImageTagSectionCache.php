<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class ImageTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 2;

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
     * @return Repositories\ImageRepository
     */
    public function getImageRepository()
    {
        return $this->imageRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->imageRepository
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
