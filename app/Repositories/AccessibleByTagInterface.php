<?php

namespace App\Repositories;

use App\Entities;

interface AccessibleByTagInterface
{
    /**
     * @param  Entities\TagEntity $tag
     * @return Entities\BaseEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag);
}
