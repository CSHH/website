<?php

namespace App\Presenters;

use App\Components\Forms;
use App\Model\Crud;
use App\Model\Entities;
use App\Model\Exceptions\ActivationLimitExpiredException;
use App\Model\Exceptions\UserNotFoundException;
use App\Model\Security\Authenticator;
use HeavenProject\Utils\FlashType;
use Nette\Mail\IMailer;
use Tracy;

class SignPresenter extends BasePresenter
{
    /** @var IMailer @inject */
    public $mailer;

    /** @var Crud\UserCrud @inject */
    public $userCrud;

    /** @var string */
    protected $appDir;

    /** @var string */
    protected $contactEmail;

    /** @var Entities\UserEntity */
    protected $e;

    protected function startup()
    {
        parent::startup();

        $this->appDir       = $this->context->parameters['appDir'];
        $this->contactEmail = $this->context->parameters['contactEmail'];
    }

    public function actionOut()
    {
        $this->getUser()->logout();

        $this->flashMessage('Byl/a jste odhlášen/a.');
        $this->redirect('Sign:in');
    }

    /**
     * @param int    $userId
     * @param string $token
     */
    public function actionUnlock($userId, $token)
    {
        $userId = $userId ?: null;
        if (empty($userId)) {
            $this->redirect('Homepage:default');
        }

        $token = $token ?: null;
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
     * @param  int                             $uid
     * @param  string                          $token
     * @throws ActivationLimitExpiredException
     */
    public function actionPassword($uid, $token)
    {
        //$this->checkLogin();

        if (empty($uid) || empty($token)) {
            $this->redirect('Sign:in');
        }

        $this->flashMessage('Zadejte prosím své nové heslo.');

        $this->e = $this->userCrud->getById($uid);

        try {
            if (!$this->e) {
                throw new UserNotFoundException('Uživatel nebyl nalezen.');
            }

            $this->userCrud->checkForTokenExpiration($this->e, $token);
        } catch (UserNotFoundException $e) {
            $this->flashMessage($e->getMessage());
            $this->redirect('Sign:in');
        } catch (ActivationLimitExpiredException $e) {
            $this->flashMessage($e->getMessage());
            $this->redirect('Sign:in');
        }
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

    /**
     * @return Forms\SignResetForm
     */
    protected function createComponentSignResetForm()
    {
        return new Forms\SignResetForm(
            $this->translator,
            $this->appDir,
            $this->contactEmail,
            $this->userCrud,
            $this->mailer
        );
    }

    /**
     * @return Forms\SignPasswordForm
     */
    protected function createComponentSignPasswordForm()
    {
        return new Forms\SignPasswordForm(
            $this->translator,
            $this->userCrud,
            $this->e
        );
    }

    private function checkLogin()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Homepage:default');
        }
    }
}
