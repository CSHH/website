<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;

class ArticleTagSectionCache implements TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ID = 1;

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
     * @return Repositories\ArticleRepository
     */
    public function getArticleRepository()
    {
        return $this->articleRepository;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tagCache->getItemsForSection(
            self::SECTION_ID,
            $this->tagCache->getTagRepository()->getAll(),
            $this->articleRepository
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
