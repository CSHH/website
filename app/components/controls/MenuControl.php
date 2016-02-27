<?php

namespace App\Components\Controls;

use App\Model\Repositories;
use Nette\Application\UI\Control;

class MenuControl extends Control
{
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
        Repositories\ArticleRepository $articleRepository,
        Repositories\ImageRepository $imageRepository,
        Repositories\VideoRepository $videoRepository,
        Repositories\WikiRepository $wikiRepository,
        Repositories\TagRepository $tagRepository
    ) {
        parent::__construct();

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

        $template->articleRepository = $this->articleRepository;
        $template->imageRepository   = $this->imageRepository;
        $template->videoRepository   = $this->videoRepository;
        $template->wikiRepository    = $this->wikiRepository;
        $template->tagRepository     = $this->tagRepository;

        $template->render();
    }
}
