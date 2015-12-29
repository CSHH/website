<?php

namespace App\Presenters;

use App\Components\Forms;
use App\Model\Crud;
use App\Model\Security\Authenticator;
use Nette\Mail\IMailer;
use App\Model\Exceptions\UserNotFoundException;
use Tracy;
use HeavenProject\Utils\FlashType;
use App\Model\Exceptions\ActivationLimitExpiredException;

class SignPresenter extends BasePresenter
{
    /** @var IMailer @inject */
    public $mailer;

    /** @var Crud\UserCrud @inject */
    public $userCrud;

    /** @var string */
    protected $contactEmail;

    protected function startup()
    {
        parent::startup();

        $this->contactEmail = $this->context->parameters['contactEmail'];
    }

    /**
     * @param int $userId
     * @param string $token
     */
    public function actionUnlock($userId, $token)
    {
        $userId = $userId ?: NULL;
        if (empty($userId)) {
            $this->redirect('Homepage:default');
        }

        $token = $token ?: NULL;
        if (empty($token)) {
            $this->redirect('Homepage:default');
        }

        try {
            $this->userCrud->unlock($userId, $token);
            $this->flashMessage('Váš účet byl úspěšně aktivován. Přihlašte se prosím.');

        } catch (UserNotFoundException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $this->flashMessage($e->getMessage(), FlashType::WARNING);
            $this->redirect('Homepage:default');

        } catch (ActivationLimitExpiredException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $this->flashMessage($e->getMessage(), FlashType::WARNING);
            $this->redirect('Homepage:default');

        } catch (\Exception $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $this->flashMessage('Došlo k chybě.', FlashType::WARNING);
            $this->redirect('Homepage:default');
        }

        $this->redirect('Homepage:default');
    }

    /**
     * @return Forms\SignUpForm
     */
	protected function createComponentSignUpForm()
	{
		return new Forms\SignUpForm(
            $this->translator,
            $this->userCrud,
            new Authenticator($this->userCrud),
            $this->mailer,
            $this->contactEmail
        );
	}

    /**
     * @return Forms\SignInForm
     */
	protected function createComponentSignInForm()
	{
		return new Forms\SignInForm(
            $this->translator,
            new Authenticator($this->userCrud)
        );
	}
}
