<?php

namespace App\Components\Forms;

use App\Entities;

interface WikiDraftFormInterface
{
    /**
     * @param  Entities\UserEntity $user
     * @param  string              $type
     * @param  Entities\WikiEntity $item
     * @return WikiDraftForm
     */
    public function create(Entities\UserEntity $user, $type, Entities\WikiEntity $item = null);
}
