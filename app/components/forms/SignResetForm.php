<?php

namespace App\Components\Forms;

use App\Model\Repositories;
use App\Model\Exceptions\FormSentBySpamException;
use App\Model\Exceptions\UserNotFoundException;
use HeavenProject\Utils\FlashType;
use Latte;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class SignResetForm extends AbstractForm
{
    /** @var string */
    private $appDir;

    /** @var string */
    private $contactEmail;

    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var IMailer */
    private $mailer;

    /**
     * @param ITranslator                 $translator
     * @param string                      $appDir
     * @param string                      $contactEmail
     * @param Repositories\UserRepository $userRepository
     * @param IMailer                     $mailer
     */
    public function __construct(
        ITranslator $translator,
        $appDir,
        $contactEmail,
        Repositories\UserRepository $userRepository,
        IMailer $mailer
    ) {
        parent::__construct($translator);

        $this->appDir         = $appDir;
        $this->contactEmail   = $contactEmail;
        $this->userRepository = $userRepository;
        $this->mailer         = $mailer;
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
                '//Sign:password',
                array(
                    'uid'   => $user->id,
                    'token' => $token,
                )
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

        } catch (UserNotFoundException $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );

        } catch (\PDOException $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
        }

        $p->redirect('Sign:in');
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

        $parameters = array(
            'subject' => $subject,
            'link'    => $link,
        );

        $email = new Message;
        $email->setFrom($from)
            ->addTo($to)
            ->setSubject($subject)
            ->setHtmlBody(
                $latte->renderToString(
                    $this->appDir . '/presenters/templates/emails/newPassword.latte',
                    $parameters
                )
            );

        $this->mailer->send($email);
    }
}
