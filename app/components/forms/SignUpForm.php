<?php

namespace App\Components\Forms;

use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\FormSentBySpamException;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\IAuthenticator;
use Nette\Mail\IMailer;
use Tracy;
use Nette\Mail\Message;
use App\Model\Crud;
use HeavenProject\Utils\FlashType;

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
     * @param ITranslator $translator
     * @param Crud\UserCrud $userCrud
     * @param IAuthenticator $authenticator
     * @param IMailer $mailer
     * @param string $contactEmail
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

        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('Zadejte prosím své uživatelské jméno.');

		$form->addText('email', 'E-mail')
            ->addRule($form::EMAIL, 'Zadaný e-mail neodpovídá požadovanému formátu.')
			->setRequired('Zadejte prosím svoji e-mailovou adresu.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Zadejte prosím své přihlašovací heslo.');

        $form->addPassword('password_confirm', 'Heslo znovu')
            ->addRule($form::EQUAL, 'Hesla se musí shodovat.', $form['password'])
            ->setRequired('Zadejte prosím Vaše přihlašovací heslo znovu pro ověření.')
            ->setOmitted();

        // Antispam
        $form->addText('__anti', '__Anti', NULL)
            ->setAttribute('style', 'display: none;');

		$form->onSuccess[] = array($this, 'formSucceeded');

		$form->addSubmit('submit', 'Registrovat');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException('Byl zaznamenán pokus o odeslání spamu prostřednictvím registračního formuláře.');
            }
            unset($values->__anti);

            $user = $this->userCrud->createRegistration($values);
            $this->sendEmail($this->contactEmail, $user->email, $user->token, $user->id);
            $p->flashMessage('Na zadanou adresu byl zaslán e-mail, pomocí kterého můžete registraci dokončit.', FlashType::SUCCESS);
            $p->redirect('Homepage:default');

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

            $form->addError('Došlo k chybě.');
        }
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
     * @param int $userId
     */
    private function sendEmail($from, $to, $token, $userId)
    {
        $text = $this->presenter->link('//Sign:unlock', array('userId' => $userId, 'token' => $token));

        $email = new Message;
        $email->setFrom($from)
            ->addTo($to)
            ->setSubject('Registrace')
            ->setBody($text);

        $this->mailer->send($email);
    }
}
