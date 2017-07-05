<?php

namespace App\Repositories;

use App\Dao\SingleUserContentDao;
use App\Entities;

class GetArticlesByTag implements AccessibleByTagInterface
{
    /** @var SingleUserContentDao */
    private $dataAccess;

    public function __construct(SingleUserContentDao $dataAccess)
    {
        $this->dataAccess = $dataAccess;
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag(Entities\ArticleEntity::class, $tag);
    }
}
