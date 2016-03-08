<?php

namespace App\AdminModule\Presenters;

use App\Components\Forms;
use App\Model\Entities;
use App\Model\Exceptions\ActivationLimitExpiredException;
use App\Model\Exceptions\UserNotFoundException;
use App\Model\Logging\Logger;
use HeavenProject\Utils\FlashType;

final class SignPresenter extends BasePresenter
{
    /** @var Entities\UserEntity */
    protected $e;

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashWithRedirect('Byl/a jste odhlášen/a.', 'Sign:in');
    }

    /**
     * @param int    $userId
     * @param string $token
     */
    public function actionUnlock($userId, $token)
    {
        $this->checkParameterAndRedirectIfNull($userId);
        $this->checkParameterAndRedirectIfNull($token);

        try {
            $this->userRepository->unlock($userId, $token);
            $this->flashMessage('Váš účet byl úspěšně aktivován. Přihlašte se prosím.');

        } catch (UserNotFoundException $e) {
            Logger::log($e->getMessage());
            $this->flashTypeWithRedirect($e->getMessage(), FlashType::WARNING, 'Homepage:default');

        } catch (ActivationLimitExpiredException $e) {
            Logger::log($e->getMessage());
            $this->flashTypeWithRedirect($e->getMessage(), FlashType::WARNING, 'Homepage:default');

        } catch (\Exception $e) {
            Logger::log($e->getMessage());
            $this->flashTypeWithRedirect('Došlo k chybě.', FlashType::WARNING, 'Homepage:default');
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
        $this->checkLogin();

        if (empty($uid) || empty($token)) {
            $this->redirect('Sign:in');
        }

        $this->flashMessage('Zadejte prosím své nové heslo.');

        $this->e = $this->userRepository->getById($uid);

        try {
            if (!$this->e) {
                throw new UserNotFoundException('Uživatel nebyl nalezen.');
            }

            $this->userRepository->checkForTokenExpiration($this->e, $token);

        } catch (UserNotFoundException $e) {
            $this->flashWithRedirect($e->getMessage(), 'Sign:in');

        } catch (ActivationLimitExpiredException $e) {
            $this->flashWithRedirect($e->getMessage(), 'Sign:in');
        }
    }

    /**
     * @return Forms\SignPasswordForm
     */
    protected function createComponentSignPasswordForm()
    {
        return new Forms\SignPasswordForm(
            $this->translator,
            $this->userRepository,
            $this->e
        );
    }

    private function checkLogin()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Homepage:default');
        }
    }

    /**
     * @param mixed $param
     */
    private function checkParameterAndRedirectIfNull($param)
    {
        $p = $param ?: null;
        if (!$p) {
            $this->redirect('Homepage:default');
        }
    }
}
