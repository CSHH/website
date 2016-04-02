<?php

namespace App\Components\Forms;

use App\Model\Entities;

interface SignPasswordFormInterface
{
    /**
     * @param  Entities\UserEntity $item
     * @return SignPasswordForm
     */
    public function create(Entities\UserEntity $item);
}
