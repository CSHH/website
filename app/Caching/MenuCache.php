<?php

namespace App\Caching;

use App\Entities;
use App\Repositories;
use Nette;
use Nette\Caching\Cache;

class MenuCache extends Nette\Object
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

    /** @var Cache */
    private $cache;

    /** @var Repositories\ArticleRepository */
    private $articleRepository;

    /** @var Repositories\ImageRepository */
    private $imageRepository;

    /** @var Repositories\VideoRepository */
    private $videoRepository;

    /** @var Repositories\WikiRepository */
    private $wikiRepository;

    /** @var Repositories\TagRepository */
    private $tagRepository;

    /** @var array */
    private $items = array();

    public function __construct(Cache $cache, Repositories\TagRepository $tagRepository)
    {
        $this->cache         = $cache;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param  Repositories\ArticleRepository $articleRepository
     * @return self
     */
    public function setArticleRepository(Repositories\ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;

        return $this;
    }

    /**
     * @param  Repositories\ImageRepository $imageRepository
     * @return self
     */
    public function setImageRepository(Repositories\ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;

        return $this;
    }

    /**
     * @param  Repositories\VideoRepository $videoRepository
     * @return self
     */
    public function setVideoRepository(Repositories\VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;

        return $this;
    }

    /**
     * @param  Repositories\WikiRepository $wikiRepository
     * @return self
     */
    public function setWikiRepository(Repositories\WikiRepository $wikiRepository)
    {
        $this->wikiRepository = $wikiRepository;

        return $this;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $tags = $this->tagRepository->getAll();

        $this->items[self::SECTION_ARTICLES] = $this->getItemsForSection(self::SECTION_ARTICLES, $tags, $this->articleRepository);
        $this->items[self::SECTION_IMAGES]   = $this->getItemsForSection(self::SECTION_IMAGES, $tags, $this->imageRepository);
        $this->items[self::SECTION_VIDEOS]   = $this->getItemsForSection(self::SECTION_VIDEOS, $tags, $this->videoRepository);
        $this->items[self::SECTION_GAMES]    = $this->getItemsForSection(self::SECTION_GAMES, $tags, $this->wikiRepository, Entities\WikiEntity::TYPE_GAME);
        $this->items[self::SECTION_MOVIES]   = $this->getItemsForSection(self::SECTION_MOVIES, $tags, $this->wikiRepository, Entities\WikiEntity::TYPE_MOVIE);
        $this->items[self::SECTION_BOOKS]    = $this->getItemsForSection(self::SECTION_BOOKS, $tags, $this->wikiRepository, Entities\WikiEntity::TYPE_BOOK);

        return $this->items;
    }

    /**
     * @param int                $section
     * @param Entities\TagEntity $tag
     */
    public function deleteSectionIfTagNotPresent($section, Entities\TagEntity $tag)
    {
        if (!$this->isTagInSection($section, $tag)) {
            $this->deleteSection($section);
        }
    }

    /**
     * @param  int                $section
     * @param  Entities\TagEntity $tag
     * @return bool
     */
    public function isTagInSection($section, Entities\TagEntity $tag)
    {
        return array_key_exists($tag->id, $this->cache->load($section));
    }

    /**
     * @param int $section
     */
    public function deleteSection($section)
    {
        $this->cache->remove($section);
    }

    /**
     * @param  int                         $section
     * @param  Entities\TagRepository[]    $tags
     * @param  Repositories\BaseRepository $repository
     * @param  string                      $wikiType
     * @return array
     */
    private function getItemsForSection($section, array $tags, Repositories\BaseRepository $repository, $wikiType = null)
    {
        $items = $this->cache->load($section);
        if ($items === null) {
            $items = array();
            foreach ($tags as $tag) {
                if ($wikiType ? $repository->getAllByTag($tag, $wikiType) : $repository->getAllByTag($tag)) {
                    $items[$tag->id] = $tag;
                }
            }
            $this->cache->save($section, $items);
        }

        return $items;
    }
}
