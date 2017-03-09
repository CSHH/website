<?php

namespace App\Components\Forms;

use App\Entities;

interface VideoFormInterface
{
    /**
     * @param  Entities\UserEntity  $user
     * @param  Entities\VideoEntity $item
     * @return VideoForm
     */
    public function create(Entities\UserEntity $user, Entities\VideoEntity $item = null);
}
