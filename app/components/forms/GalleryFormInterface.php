<?php

namespace App\Components\Forms;

use App\Entities;

interface GalleryFormInterface
{
    /**
     * @param  Entities\UserEntity $user
     * @return GalleryForm
     */
    public function create(Entities\UserEntity $user);
}
