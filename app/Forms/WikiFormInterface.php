<?php

namespace App\Forms;

use App\Entities;
use App\Repositories;

interface WikiFormInterface
{
    /**
     * @param  Repositories\WikiRepository $repository
     * @param  Entities\UserEntity         $user
     * @param  string                      $type
     * @param  Entities\WikiEntity         $item
     * @return WikiForm
     */
    public function create(Repositories\WikiRepository $repository, Entities\UserEntity $user, $type, Entities\WikiEntity $item = null);
}
