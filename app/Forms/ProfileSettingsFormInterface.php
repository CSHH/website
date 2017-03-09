<?php

namespace App\Forms;

use App\Entities;

interface ProfileSettingsFormInterface
{
    /**
     * @param  Entities\UserEntity $item
     * @return ProfileSettingsForm
     */
    public function create(Entities\UserEntity $item);
}
