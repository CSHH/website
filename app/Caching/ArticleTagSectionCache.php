<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class ArticleTagSectionCache implements TagSectionCacheInterface
{
    /** @var TagCache */
    private $tagCache;

    /** @var Repositories\ArticleRepository */
    private $articleRepository;

    public function __construct(TagCache $tagCache)
    {
        $this->tagCache = $tagCache;
    }

    /**
     * @param Repositories\ArticleRepository $articleRepository
     */
    public function setArticleRepository(Repositories\ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            TagSectionCacheInterface::SECTION_ARTICLES,
            $this->tagCache->getTagRepository()->getAll(),
            $this->articleRepository
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
