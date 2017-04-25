<?php

namespace App\Caching;

use App\Entities;

interface TagSectionCacheInterface
{
    /** @var int */
    const SECTION_ARTICLES = 1;
    /** @var int */
    const SECTION_IMAGES = 2;
    /** @var int */
    const SECTION_VIDEOS = 3;
    /** @var int */
    const SECTION_GAMES = 4;
    /** @var int */
    const SECTION_MOVIES = 5;
    /** @var int */
    const SECTION_BOOKS = 6;

    /**
     * @return array
     */
    public function getTags();

    /**
     * @param  int                $section
     * @param  Entities\TagEntity $tag
     * @return bool
     */
    public function isTagInSection($section, Entities\TagEntity $tag);

    /**
     * @param int                $section
     * @param Entities\TagEntity $tag
     */
    public function deleteSectionIfTagNotPresent($section, Entities\TagEntity $tag);

    /**
     * @param int $section
     */
    public function deleteSection($section);
}
