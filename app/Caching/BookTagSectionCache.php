<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class BookTagSectionCache implements TagSectionCacheInterface
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
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            TagSectionCacheInterface::SECTION_BOOKS,
            $this->tagCache->getTagRepository()->getAll(),
            $this->wikiRepository,
            Entities\WikiEntity::TYPE_BOOK
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
