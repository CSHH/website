<?php

namespace App\Front;

use App;
use App\Forms;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Forms\SignUpFormInterface @inject */
    public $signUpForm;

    /** @var Forms\SignInFormInterface @inject */
    public $signInForm;

    /** @var Forms\SignResetFormInterface @inject */
    public $signResetForm;

    /** @var bool */
    protected $canAccess = false;

    protected function startup()
    {
        parent::startup();

        $this->registerFormExtendingMethods();
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
