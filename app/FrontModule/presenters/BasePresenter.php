<?php

namespace App\FrontModule\Presenters;

use App;
use App\Components\Controls;
use App\Components\Forms;
use App\Model\Security\Authenticator;
use Nette\Mail\IMailer;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var IMailer @inject */
    public $mailer;

    /** @var Controls\MenuControlInterface @inject */
    public $menuControl;

    /** @var bool */
    protected $canAccess = false;

    /** @var string */
    protected $appDir;

    /** @var string */
    protected $contactEmail;

    protected function startup()
    {
        parent::startup();

        $parameters = $this->context->parameters;

        $this->appDir       = $parameters['appDir'];
        $this->contactEmail = $parameters['contactEmail'];

        $this->registerFormExtendingMethods();
    }

    /**
     * @return Controls\MenuControlInterface
     */
    protected function createComponentMenuControl()
    {
        return $this->menuControl->create();
    }

    /**
     * @return Forms\SignUpForm
     */
    protected function createComponentSignUpForm()
    {
        return new Forms\SignUpForm(
            $this->translator,
            $this->userRepository,
            new Authenticator($this->translator, $this->userRepository),
            $this->mailer,
            $this->contactEmail
        );
    }

    /**
     * @return Forms\SignInForm
     */
    protected function createComponentSignInForm()
    {
        return new Forms\SignInForm(
            $this->translator,
            new Authenticator($this->translator, $this->userRepository)
        );
    }

    /**
     * @return Forms\SignResetForm
     */
    protected function createComponentSignResetForm()
    {
        return new Forms\SignResetForm(
            $this->translator,
            $this->appDir,
            $this->contactEmail,
            $this->userRepository,
            $this->mailer
        );
    }
}
