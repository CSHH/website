<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class MovieTagSectionCache implements TagSectionCacheInterface
{
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
            TagSectionCacheInterface::SECTION_MOVIES,
            $this->tagCache->getTagRepository()->getAll(),
            $this->wikiRepository,
            Entities\WikiEntity::TYPE_MOVIE
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
