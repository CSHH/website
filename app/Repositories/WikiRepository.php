<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\WikiDao;
use App\Entities;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

abstract class WikiRepository extends BaseRepository
{
    /** @var EntityManager */
    protected $em;

    /** @var WikiDao */
    protected $dataAccess;

    /** @var Caching\TagSectionCacheInterface */
    protected $tagCache;

    public function __construct(
        EntityDao $dao,
        WikiDao $dataAccess,
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
