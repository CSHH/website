<?php

namespace App\Model\Repositories;

use App\Model\Entities;
use Kdyby\Doctrine\EntityDao;

class TagRepository extends BaseRepository
{
    public function __construct(EntityDao $dao)
    {
        parent::__construct($dao);
    }

    /**
     * @param  string                  $slug
     * @return Entities\TagEntity|null
     */
    public function getBySlug($slug)
    {
        return $this->dao->findOneBy(array('slug' => $slug));
    }
}
