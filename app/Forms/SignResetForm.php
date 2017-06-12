<?php

namespace App\Forms;

use App\Emails\ForgottenPasswordEmail;
use App\Exceptions\FormSentBySpamException;
use App\Exceptions\UserNotFoundException;
use App\Links\ForgottenPasswordLinkGenerator;
use App\Repositories;
use App\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;

class SignResetForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var ForgottenPasswordEmail */
    private $forgottenPasswordEmail;

    /** @var ForgottenPasswordLinkGenerator */
    private $forgottenPasswordLinkGenerator;

    /**
     * @param ITranslator                    $translator
     * @param Repositories\UserRepository    $userRepository
     * @param ForgottenPasswordEmail         $forgottenPasswordEmail
     * @param ForgottenPasswordLinkGenerator $forgottenPasswordLinkGenerator
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        ForgottenPasswordEmail $forgottenPasswordEmail,
        ForgottenPasswordLinkGenerator $forgottenPasswordLinkGenerator
    ) {
        parent::__construct($translator);

        $this->userRepository                 = $userRepository;
        $this->forgottenPasswordEmail         = $forgottenPasswordEmail;
        $this->forgottenPasswordLinkGenerator = $forgottenPasswordLinkGenerator;
    }

    protected function configure(Form $form)
    {
        $form->addText('email', 'locale.form.email')
            ->addRule($form::EMAIL, 'locale.form.email_not_in_order')
            ->setRequired('locale.form.email_required');

        $form->addText('__anti', '__Anti', null)
            ->setAttribute('style', 'display: none;');

        $form->addSubmit('submit', 'locale.form.send');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();

            $this->checkSpam($values);

            $user = $this->userRepository->getByEmail($values->email);
            if (!$user) {
                throw new UserNotFoundException;
            }

            $token = $this->userRepository->prepareNewToken($user);

            $link = $this->forgottenPasswordLinkGenerator->generateLink($user->email, $token);
            $this->forgottenPasswordEmail->send($values->email, $link);

            $p->flashMessage(
                $this->translator->translate('locale.sign.new_password_request_email_sent'),
                FlashType::INFO
            );
        } catch (FormSentBySpamException $e) {
            $this->addFormError($form, $e);
            $this->redrawControl('formErrors');
        } catch (UserNotFoundException $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
            $this->redrawControl('formErrors');
        } catch (\PDOException $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
            $this->redrawControl('formErrors');
        }

        $p->redirect(':Front:Homepage:default');
    }

    protected function insideRender(ITemplate $template)
    {
        $template->form = $this->form;
    }

    /**
     * @param  ArrayHash               $values
     * @throws FormSentBySpamException
     */
    private function checkSpam(ArrayHash $values)
    {
        if (strlen($values->__anti) > 0) {
            throw new FormSentBySpamException(
                $this->translator->translate('locale.form.spam_attempt_sign_reset')
            );
        }
        unset($values->__anti);
    }
}
