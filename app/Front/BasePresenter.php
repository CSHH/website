<?php

namespace App\Front;

use App;
use App\Forms;
use App\Repositories;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Forms\SignUpFormInterface @inject */
    public $signUpForm;

    /** @var Forms\SignInFormInterface @inject */
    public $signInForm;

    /** @var Forms\SignResetFormInterface @inject */
    public $signResetForm;

    /** @var Repositories\ArticleRepository @inject */
    public $articleRepository;

    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var bool */
    protected $canAccess = false;

    protected function startup()
    {
        parent::startup();

        $this->canAccess = $this->accessChecker->canAccess();
        $this->registerFormExtendingMethods();
    }

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->canAccess = $this->canAccess;

        if ($this->canAccess) {
            $this->template->inactiveArticlesCount = count($this->articleRepository->getAllInactive());
            $this->template->inactiveImagesCount   = count($this->imageRepository->getAllInactive());
            $this->template->inactiveVideosCount   = count($this->videoRepository->getAllInactive());
            $this->template->inactiveGamesCount    = count($this->wikiRepository->getAllInactiveGames());
            $this->template->inactiveMoviesCount   = count($this->wikiRepository->getAllInactiveMovies());
            $this->template->inactiveBooksCount    = count($this->wikiRepository->getAllInactiveBooks());
        }
    }

    /**
     * @return Forms\SignUpForm
     */
    protected function createComponentSignUpForm()
    {
        return $this->signUpForm->create();
    }

    /**
     * @return Forms\SignInForm
     */
    protected function createComponentSignInForm()
    {
        return $this->signInForm->create();
    }

    /**
     * @return Forms\SignResetForm
     */
    protected function createComponentSignResetForm()
    {
        return $this->signResetForm->create();
    }
}
