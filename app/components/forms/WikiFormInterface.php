<?php

namespace App\Components\Forms;

use App\Model\Entities;

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
