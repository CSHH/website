<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Security\Authenticator;

final class SettingsPresenter extends SecurePresenter
{
    /**
     * @return Forms\ProfileSettingsForm
     */
    protected function createComponentProfileSettingsForm()
    {
        return new Forms\ProfileSettingsForm(
            $this->translator,
            $this->userRepository,
            new Authenticator($this->translator, $this->userRepository),
            $this->getUser()->getStorage(),
            $this->getLoggedUserEntity()
        );
    }
}
