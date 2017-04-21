<?php

namespace App\Components;

use App\Caching\TagCache;
use App\Repositories;
use Nette\Application\UI\Control;

class MenuControl extends Control
{
    /** @var TagCache */
    private $tagCache;

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

    public function __construct(
        TagCache $tagCache,
        Repositories\ArticleRepository $articleRepository,
        Repositories\ImageRepository $imageRepository,
        Repositories\VideoRepository $videoRepository,
        Repositories\WikiRepository $wikiRepository,
        Repositories\TagRepository $tagRepository
    ) {
        parent::__construct();

        $tagCache->setArticleRepository($articleRepository);
        $tagCache->setImageRepository($imageRepository);
        $tagCache->setVideoRepository($videoRepository);
        $tagCache->setWikiRepository($wikiRepository);

        $this->tagCache          = $tagCache;
        $this->articleRepository = $articleRepository;
        $this->imageRepository   = $imageRepository;
        $this->videoRepository   = $videoRepository;
        $this->wikiRepository    = $wikiRepository;
        $this->tagRepository     = $tagRepository;
    }

    public function render()
    {
        $template = $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/MenuControl.latte');

        $template->menuItems = $this->tagCache->getAll();

        $template->render();
    }
}
