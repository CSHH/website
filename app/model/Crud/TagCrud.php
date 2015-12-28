<?php

namespace App\Model\Crud;

use App\Model\Entities;
use Kdyby\Doctrine\EntityDao;

class TagCrud extends BaseCrud
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
