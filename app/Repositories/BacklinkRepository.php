<?php

namespace App\Repositories;

use App\Entities;
use Kdyby\Doctrine\EntityDao;

class BacklinkRepository extends BaseRepository
{
    public function __construct(EntityDao $dao)
    {
        parent::__construct($dao);
    }

    /**
     * @param  string                  $oldPath
     * @return Entities\BacklinkEntity|null
     */
    public function getByOldPath($oldPath)
    {
        return $this->dao->findOneBy(['oldPath' => $oldPath]);
    }
}
