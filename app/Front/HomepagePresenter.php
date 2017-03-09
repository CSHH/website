<?php

namespace App\Front;

use App\Repositories;
use App\Videos\VideoThumbnail;

final class HomepagePresenter extends BasePresenter
{
    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var VideoThumbnail @inject */
    public $videoThumbnail;

    public function renderDefault()
    {
        $this->template->uploadDir      = $this->context->parameters['uploadDir'];
        $this->template->videoThumbnail = $this->videoThumbnail;
        $this->template->news           = $this->articleRepository->getAllNews();
        $this->template->latestArticles = $this->articleRepository->getLatestArticles();
        $this->template->latestImages   = $this->imageRepository->getLatestImages();
        $this->template->latestVideos   = $this->videoRepository->getLatestVideos();
        $this->template->latestGames    = $this->wikiRepository->getLatestGames();
        $this->template->latestMovies   = $this->wikiRepository->getLatestMovies();
        $this->template->latestBooks    = $this->wikiRepository->getLatestBooks();
    }
}
