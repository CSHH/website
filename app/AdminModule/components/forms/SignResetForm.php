<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Crud;
use App\Model\Exceptions\FormSentBySpamException;
use App\Model\Exceptions\UserNotFoundException;
use HeavenProject\Utils\FlashType;
use Latte;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Tracy;

class SignResetForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var string */
    private $appDir;

    /** @var string */
    private $contactEmail;

    /** @var Crud\UserCrud */
    private $userCrud;

    /** @var IMailer */
    private $mailer;

    /**
     * @param ITranslator   $translator
     * @param string        $appDir
     * @param string        $contactEmail
     * @param Crud\UserCrud $userCrud
     * @param IMailer       $mailer
     */
    public function __construct(
        ITranslator $translator,
        $appDir,
        $contactEmail,
        Crud\UserCrud $userCrud,
        IMailer $mailer
    ) {
        parent::__construct();

        $this->translator   = $translator;
        $this->appDir       = $appDir;
        $this->contactEmail = $contactEmail;
        $this->userCrud     = $userCrud;
        $this->mailer       = $mailer;
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

        // Antispam
        $form->addText('__anti', '__Anti', null)
            ->setAttribute('style', 'display: none;');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.send');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException(
                    $this->translator->translate('locale.form.spam_attempt_sign_reset')
                );
            }
            unset($values->__anti);

            $user = $this->userCrud->getByEmail($values->email);
            if (!$user) {
                throw new UserNotFoundException;
            }

            $token = $this->userCrud->prepareNewToken($user);

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
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        } catch (UserNotFoundException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($this->translator->translate('locale.error.occurred'));
        } catch (\PDOException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($this->translator->translate('locale.error.occurred'));
        }

        $p->redirect('Sign:in');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignResetForm.latte');

        $template->render();
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
