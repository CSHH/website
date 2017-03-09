<?php

namespace App\Forms;

use App\Entities;

interface WikiFormInterface
{
    /**
     * @param  Entities\UserEntity $user
     * @param  string              $type
     * @param  Entities\WikiEntity $item
     * @return WikiForm
     */
    public function create(Entities\UserEntity $user, $type, Entities\WikiEntity $item = null);
}
