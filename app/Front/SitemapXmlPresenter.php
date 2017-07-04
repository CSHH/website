<?php

namespace App\Front;

use App\Repositories;
use Nette;

final class SitemapXmlPresenter extends Nette\Application\UI\Presenter
{
    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Repositories\BookRepository @inject */
    public $bookRepository;

    /** @var Repositories\GameRepository @inject */
    public $gameRepository;

    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Repositories\MovieRepository @inject */
    public $movieRepository;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    public function renderDefault()
    {
        $this->template->uploadDir         = $this->context->parameters['uploadDir'];
        $this->template->gameRepository    = $this->gameRepository;
        $this->template->movieRepository   = $this->movieRepository;
        $this->template->bookRepository    = $this->bookRepository;
        $this->template->articleRepository = $this->articleRepository;
        $this->template->imageRepository   = $this->imageRepository;
        $this->template->videoRepository   = $this->videoRepository;
    }
}
