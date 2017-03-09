<?php

namespace App\Admin;

use App;
use App\Repositories;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var Repositories\TagRepository @inject */
    public $tagRepository;

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->articleRepository = $this->articleRepository;
        $this->template->imageRepository   = $this->imageRepository;
        $this->template->videoRepository   = $this->videoRepository;
        $this->template->wikiRepository    = $this->wikiRepository;
        $this->template->tagRepository     = $this->tagRepository;

        $this->template->uploadDir = $this->context->parameters['uploadDir'];
    }
}
