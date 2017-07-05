<?php

namespace App\Repositories;

use App\Entities;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

class BacklinkRepository extends BaseRepository
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityDao $dao, EntityManager $em)
    {
        parent::__construct($dao);

        $this->em = $em;
    }

    /**
     * @param Entities\BaseEntity $e
     * @param Entities\TagEntity  $oldTag
     * @param string              $oldSlug
     * @param string              $urlNamespace
     */
    public function create(Entities\BaseEntity $e, Entities\TagEntity $oldTag, $oldSlug, $urlNamespace)
    {
        if ($e->isActive && ($e->tag->id !== $oldTag->id || $e->slug !== $oldSlug)) {
            $oldPath = '/' . $urlNamespace . '/' . $oldTag->slug . '/' . $oldSlug;
            $newPath = '/' . $urlNamespace . '/' . $e->tag->slug . '/' . $e->slug;

            $collisionBacklink = $this->getByOldPath($newPath);
            if ($collisionBacklink) {
                $this->removeAndFlush($this->em, $collisionBacklink);
            }

            $backlink          = new Entities\BacklinkEntity;
            $backlink->oldPath = $oldPath;
            $backlink->newPath = $newPath;

            $this->persistAndFlush($this->em, $backlink);
        }
    }

    /**
     * @param  string                       $oldPath
     * @return Entities\BacklinkEntity|null
     */
    public function getByOldPath($oldPath)
    {
        return $this->dao->findOneBy(['oldPath' => $oldPath]);
    }
}
