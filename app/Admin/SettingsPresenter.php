<?php

namespace App\Admin;

use App\Forms;

final class SettingsPresenter extends SecurePresenter
{
    /** @var Forms\ProfileSettingsFormInterface @inject */
    public $profileSettingsForm;

    /**
     * @return Forms\ProfileSettingsForm
     */
    protected function createComponentProfileSettingsForm()
    {
        return $this->profileSettingsForm->create($this->getLoggedUserEntity());
    }
}
