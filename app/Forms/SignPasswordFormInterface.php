<?php

namespace App\Forms;

use App\Entities;

interface SignPasswordFormInterface
{
    /**
     * @param  Entities\UserEntity $item
     * @return SignPasswordForm
     */
    public function create(Entities\UserEntity $item);
}
