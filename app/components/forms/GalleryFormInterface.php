<?php

namespace App\Components\Forms;

use App\Model\Entities;

interface GalleryFormInterface
{
    /**
     * @param  Entities\UserEntity $user
     * @return GalleryForm
     */
    public function create(Entities\UserEntity $user);
}
