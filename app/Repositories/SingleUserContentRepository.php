<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\SingleUserContentDao;
use App\Entities;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

abstract class SingleUserContentRepository extends BaseRepository
{
    /** @var EntityManager */
    protected $em;

    /** @var SingleUserContentDao */
    protected $dataAccess;

    /** @var Caching\TagSectionCacheInterface */
    protected $tagCache;

    public function __construct(
        EntityDao $dao,
        SingleUserContentDao $dataAccess,
        EntityManager $em,
        Caching\TagSectionCacheInterface $tagCache
    ) {
        parent::__construct($dao);

        $this->dataAccess = $dataAccess;
        $this->em         = $em;
        $this->tagCache   = $tagCache;
    }

    /**
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    protected function doActivate(Entities\BaseEntity $e)
    {
        $e->isActive = true;

        $this->persistAndFlush($this->em, $e);

        $this->tagCache->deleteSectionIfTagNotPresent($e->tag);

        return $e;
    }
}
