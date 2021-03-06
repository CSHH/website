<?php

namespace App\Admin;

use App\Entities;
use App\Exceptions\ActivationLimitExpiredException;
use App\Exceptions\UserNotFoundException;
use App\Forms;
use App\Repositories;
use App\Utils\FlashType;

final class SignPresenter extends BasePresenter
{
    /** @var Forms\SignPasswordFormInterface @inject */
    public $signPasswordForm;

    /** @var Repositories\UserRepository @inject */
    public $userRepository;

    /** @var Entities\UserEntity */
    protected $e;

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashWithRedirect($this->translator->translate('locale.sign.out'), ':Front:Homepage:default');
    }

    /**
     * @param string $email
     * @param string $token
     */
    public function actionUnlock($email, $token)
    {
        $this->checkParameterAndRedirectIfNull($email);
        $this->checkParameterAndRedirectIfNull($token);

        try {
            $this->userRepository->unlock($email, $token);
            $this->flashMessage($this->translator->translate('locale.sign.account_activated'));
        } catch (UserNotFoundException $e) {
            dlog($e->getMessage());
            $this->flashTypeWithRedirect($e->getMessage(), FlashType::WARNING, 'Homepage:default');
        } catch (ActivationLimitExpiredException $e) {
            dlog($e->getMessage());
            $this->flashTypeWithRedirect($e->getMessage(), FlashType::WARNING, 'Homepage:default');
        } catch (\Exception $e) {
            dlog($e->getMessage());
            $this->flashTypeWithRedirect($this->translator->translate('locale.error.occurred'), FlashType::WARNING, 'Homepage:default');
        }

        $this->redirect('Homepage:default');
    }

    /**
     * @param  string                          $email
     * @param  string                          $token
     * @throws ActivationLimitExpiredException
     */
    public function actionPassword($email, $token)
    {
        $this->checkLogin();

        if (empty($email) || empty($token)) {
            $this->redirect(':Front:Homepage:default');
        }

        $this->flashMessage($this->translator->translate('locale.sign.password'));

        $this->e = $this->userRepository->getByEmail($email);

        try {
            if (!$this->e) {
                throw new UserNotFoundException($this->translator->translate('locale.sign.account_not_found'));
            }

            $this->userRepository->checkForTokenExpiration($this->e, $token);
        } catch (UserNotFoundException $e) {
            $this->flashWithRedirect($e->getMessage(), ':Front:Homepage:default');
        } catch (ActivationLimitExpiredException $e) {
            $this->flashWithRedirect($e->getMessage(), ':Front:Homepage:default');
        }
    }

    /**
     * @return Forms\SignPasswordForm
     */
    protected function createComponentSignPasswordForm()
    {
        return $this->signPasswordForm->create($this->e);
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
