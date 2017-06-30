<?php

namespace App\Repositories;

use App\Dao\WikiDao;
use App\Entities;

class GetGamesByTag implements AccessibleByTagInterface
{
    /** @var WikiDao */
    private $dataAccess;

    public function __construct(WikiDao $dataAccess)
    {
        $this->dataAccess = $dataAccess;
    }

    /**
     * @param  Entities\TagEntity $tag
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag($tag, Entities\WikiEntity::TYPE_GAME);
    }
}
