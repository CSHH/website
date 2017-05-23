<?php

namespace App\Forms;

use App\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Security\AuthenticationException;

class SignInForm extends AbstractForm
{
    public function __construct(ITranslator $translator)
    {
        parent::__construct($translator);
    }

    protected function configure(Form $form)
    {
        $form->addText('email', 'locale.form.email')
            ->addRule($form::EMAIL, 'locale.form.email_not_in_order')
            ->setRequired('locale.form.email_required');

        $form->addPassword('password', 'locale.form.password')
            ->setRequired('locale.form.password_required');

        $form->addCheckbox('remember', 'locale.form.remember');

        $form->addSubmit('submit', 'locale.form.sign_in');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $u      = $p->getUser();
            $values = $form->getValues();

            if ($values->remember) {
                $u->setExpiration('14 days', false);
            } else {
                $u->setExpiration('60 minutes', true);
            }

            $u->login($values->email, $values->password);
            $p->flashMessage(
                $this->translator->translate('locale.sign.in'),
                FlashType::INFO
            );
        } catch (AuthenticationException $e) {
            $this->addFormError($form, $e);
            $this->redrawControl('formErrors');
        }

        if ($u->isLoggedIn()) {
            $p->redirect('Homepage:default');
        }
    }

    protected function insideRender(ITemplate $template)
    {
        $template->form = $this->form;
    }
}
