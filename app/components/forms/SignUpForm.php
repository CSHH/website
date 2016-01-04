<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\FormSentBySpamException;
use HeavenProject\Utils\FlashType;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Security\IAuthenticator;
use Tracy;

class SignUpForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\UserCrud */
    private $userCrud;

    /** @var IAuthenticator */
    private $authenticator;

    /** @var IMailer */
    private $mailer;

    /** @var string */
    private $contactEmail;

    /**
     * @param ITranslator    $translator
     * @param Crud\UserCrud  $userCrud
     * @param IAuthenticator $authenticator
     * @param IMailer        $mailer
     * @param string         $contactEmail
     */
    public function __construct(
        ITranslator $translator,
        Crud\UserCrud $userCrud,
        IAuthenticator $authenticator,
        IMailer $mailer,
        $contactEmail
    ) {
        parent::__construct();

        $this->translator    = $translator;
        $this->userCrud      = $userCrud;
        $this->authenticator = $authenticator;
        $this->mailer        = $mailer;
        $this->contactEmail  = $contactEmail;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addText('username', 'locale.form.username')
            ->setRequired('locale.form.username_required');

        $form->addText('email', 'locale.form.email')
            ->addRule($form::EMAIL, 'locale.form.email_not_in_order')
            ->setRequired('locale.form.email_address');

        $form->addPassword('password', 'locale.form.password')
            ->setRequired('locale.form.password_required');

        $form->addPassword('password_confirm', 'locale.form.password_confirm')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password'])
            ->setRequired('locale.form.password_confirm_required')
            ->setOmitted();

        // Antispam
        $form->addText('__anti', '__Anti', null)
            ->setAttribute('style', 'display: none;');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.submit_sign_up');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException(
                    $this->translator->translate('locale.form.spam_attempt_sign_up')
                );
            }
            unset($values->__anti);

            $user = $this->userCrud->createRegistration($values);
            $this->sendEmail($this->contactEmail, $user->email, $user->token, $user->id);

            $p->flashMessage(
                $this->translator->translate('locale.sign.sign_up_email_sent'),
                FlashType::SUCCESS
            );
        } catch (FormSentBySpamException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        } catch (PossibleUniqueKeyDuplicationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        } catch (\Exception $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($this->translator->translate('locale.error.occurred'));
        }

        $p->redirect('Homepage:default');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignUpForm.latte');

        $template->render();
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $token
     * @param int    $userId
     */
    private function sendEmail($from, $to, $token, $userId)
    {
        $text = $this->presenter->link(
            '//Sign:unlock',
            array(
                'userId' => $userId,
                'token'  => $token,
            ));

        $email = new Message;
        $email->setFrom($from)
            ->addTo($to)
            ->setSubject($this->translator->translate('locale.sign.sign_up_request'))
            ->setBody($text);

        $this->mailer->send($email);
    }
}
