<?php

namespace App\FrontModule\Presenters;

use App\Model\Repositories;
use App\Model\Videos\VideoThumbnail;

final class HomepagePresenter extends BasePresenter
{
    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    public function renderDefault()
    {
        $parameters = $this->context->parameters;

        $this->template->uploadDir = $parameters['uploadDir'];

        $this->template->videoThumbnail = new VideoThumbnail($parameters['wwwDir'], $parameters['videoThumbnailsDir'], $parameters['vimeoOembedEndpoint']);

        $this->template->news           = $this->articleRepository->getAllNews();
        $this->template->latestArticles = $this->articleRepository->getLatestArticles();
        $this->template->latestImages   = $this->imageRepository->getLatestImages();
        $this->template->latestVideos   = $this->videoRepository->getLatestVideos();
    }
}
