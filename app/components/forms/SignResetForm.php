<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Exceptions\FormSentBySpamException;
use App\Model\Exceptions\UserNotFoundException;
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

        $form->addText('email', 'E-mail')
            ->addRule($form::EMAIL, 'Zadaný e-mail neodpovídá požadovanému formátu.')
            ->setRequired('Zadejte prosím svůj přihlašovací e-mail');

        // Antispam
        $form->addText('__anti', '__Anti', null)
            ->setAttribute('style', 'display: none;');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'Odeslat');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $values = $form->getValues();

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException(
                    'Byl zaznamenán pokus o odeslání spamu prostřednictvím formuláře pro vyžádání nového hesla.'
                );
            }
            unset($values->__anti);

            $user  = $this->userCrud->getByEmail($values->email);
            if (!$user) {
                throw new UserNotFoundException;
            }

            $token = $this->userCrud->prepareNewToken($user);

            $link  = $this->getPresenter()->link(
                '//Sign:password',
                array(
                    'uid'   => $user->id,
                    'token' => $token,
                )
            );

            $this->sendEmail(
                $this->contactEmail,
                $values->email,
                'Vyžádání nového hesla',
                $link
            );

            $form->getPresenter()->flashMessage('Na zadanou adresu byl odeslán e-mail.');

        } catch (FormSentBySpamException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());

        } catch (UserNotFoundException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError('Došlo k chybě.');

        } catch (\PDOException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError('Došlo k chybě.');
        }

        $form->getPresenter()->redirect('Sign:in');
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
