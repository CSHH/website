<?php

namespace App\FrontModule\Presenters;

use App;
use App\Model\Entities;
use App\Model\Forms\ExtendingMethods as FormExtendingMethods;
use App\Model\Repositories;

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

    /** @var bool */
    protected $canAccess = false;

    protected function startup()
    {
        parent::startup();

        $ext = new FormExtendingMethods;
        $ext->registerMethods();
    }

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

    /**
     * @return bool
     */
    protected function canAccess()
    {
        $user = $this->getLoggedUserEntity();

        return $user && $user->role > Entities\UserEntity::ROLE_USER;
    }
}
