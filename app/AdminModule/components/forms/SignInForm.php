<?php

namespace App\AdminModule\Components\Forms;

use HeavenProject\Utils\FlashType;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Tracy;

class SignInForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var IAuthenticator */
    private $authenticator;

    public function __construct(
        ITranslator $translator,
        IAuthenticator $authenticator
    ) {
        parent::__construct();

        $this->translator    = $translator;
        $this->authenticator = $authenticator;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addText('email', 'locale.form.email')
            ->addRule($form::EMAIL, 'locale.form.email_not_in_order')
            ->setRequired('locale.form.email_required');

        $form->addPassword('password', 'locale.form.password')
            ->setRequired('locale.form.password_required');

        $form->addCheckbox('remember', 'locale.form.remember');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.sign_in');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            $p->getUser()->setAuthenticator($this->authenticator);

            if ($values->remember) {
                $p->getUser()->setExpiration('14 days', false);
            } else {
                $p->getUser()->setExpiration('60 minutes', true);
            }

            $p->getUser()->login($values->email, $values->password);
            $p->flashMessage(
                $this->translator->translate('locale.sign.in'),
                FlashType::INFO
            );
        } catch (AuthenticationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        }

        $p->redirect('Homepage:default');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignInForm.latte');

        $template->render();
    }
}
