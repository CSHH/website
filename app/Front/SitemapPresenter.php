<?php

namespace App\Front;

final class SitemapPresenter extends BasePresenter
{
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
