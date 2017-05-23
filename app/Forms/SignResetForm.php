<?php

namespace App\Forms;

use App\Exceptions\FormSentBySpamException;
use App\Exceptions\UserNotFoundException;
use App\Repositories;
use App\Utils\FlashType;
use Latte;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class SignResetForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var IMailer */
    private $mailer;

    /** @var string */
    private $appDir;

    /** @var string */
    private $contactEmail;

    /**
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     * @param IMailer                     $mailer
     * @param string                      $appDir
     * @param string                      $contactEmail
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        IMailer $mailer,
        $appDir,
        $contactEmail
    ) {
        parent::__construct($translator);

        $this->userRepository = $userRepository;
        $this->mailer         = $mailer;
        $this->appDir         = $appDir;
        $this->contactEmail   = $contactEmail;
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

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException(
                    $this->translator->translate('locale.form.spam_attempt_sign_reset')
                );
            }
            unset($values->__anti);

            $user = $this->userRepository->getByEmail($values->email);
            if (!$user) {
                throw new UserNotFoundException;
            }

            $token = $this->userRepository->prepareNewToken($user);

            $link = $p->link(
                '//:Admin:Sign:password',
                [
                    'uid'   => $user->id,
                    'token' => $token,
                ]
            );

            $this->sendEmail(
                $this->contactEmail,
                $values->email,
                $this->translator->translate('locale.sign.new_password_request'),
                $link
            );

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
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $link
     */
    private function sendEmail($from, $to, $subject, $link)
    {
        $latte = new Latte\Engine;

        $parameters = [
            'subject' => $subject,
            'link'    => $link,
        ];

        $email = new Message;
        $email->setFrom($from)
            ->addTo($to)
            ->setSubject($subject)
            ->setHtmlBody(
                $latte->renderToString(
                    $this->appDir . '/Presenters/templates/emails/forgottenPassword.latte',
                    $parameters
                )
            );

        $this->mailer->send($email);
    }
}
