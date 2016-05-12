<?php

namespace App\Components\Controls;

use App\Model\Caching\MenuCache;
use App\Model\Repositories;
use Nette\Application\UI\Control;

class MenuControl extends Control
{
    /** @var MenuCache */
    private $menuCache;

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
        MenuCache $menuCache,
        Repositories\ArticleRepository $articleRepository,
        Repositories\ImageRepository $imageRepository,
        Repositories\VideoRepository $videoRepository,
        Repositories\WikiRepository $wikiRepository,
        Repositories\TagRepository $tagRepository
    ) {
        parent::__construct();

        $menuCache->setArticleRepository($articleRepository);
        $menuCache->setImageRepository($imageRepository);
        $menuCache->setVideoRepository($videoRepository);
        $menuCache->setWikiRepository($wikiRepository);

        $this->menuCache         = $menuCache;
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

        $template->menuItems = $this->menuCache->getAll();

        $template->render();
    }
}
