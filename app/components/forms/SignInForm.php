<?php

namespace App\Components\Forms;

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

        $form->addText('email', 'E-mail')
            ->addRule($form::EMAIL, 'Zadaný e-mail neodpovídá požadovanému formátu.')
            ->setRequired('Zadejte prosím svůj přihlašovací e-mail');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Zadejte prosím své přihlašovací heslo');

        $form->addCheckbox('remember', 'Neodhlašovat');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'Přihlásit');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $values = $form->getValues();

            $presenter = $form->getPresenter();
            $presenter->getUser()->setAuthenticator($this->authenticator);

            if ($values->remember) {
                $presenter->getUser()->setExpiration('14 days', false);
            } else {
                $presenter->getUser()->setExpiration('60 minutes', true);
            }

            $presenter->getUser()->login($values->email, $values->password);

            $form->presenter->flashMessage('Vítejte');

            $form->presenter->redirect('Homepage:default');

        } catch (AuthenticationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        }
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignInForm.latte');

        $template->render();
    }
}
