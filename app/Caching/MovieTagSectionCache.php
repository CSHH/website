<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class MovieTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 5;

    /** @var TagCache */
    private $tagCache;

    /** @var Repositories\GetMoviesByTag */
    private $repository;

    public function __construct(TagCache $tagCache, Repositories\GetMoviesByTag $repository)
    {
        $this->tagCache   = $tagCache;
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItems(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->repository,
            Entities\WikiEntity::TYPE_MOVIE
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
