<?php

namespace App\Model\Repositories;

use App\Model\Entities;
use Kdyby\Doctrine\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use HeavenProject\FileManagement\FileManager;

class ImageRepository extends BaseRepository
{
    /** @var EntityManager */
    private $em;

    /** @var EntityDao */
    private $fileDao;

    /** @var string */
    private $uploadDir;

    /**
     * @param string        $wwwDir
     * @param string        $uploadDir
     * @param EntityDao     $dao
     * @param EntityDao     $fileDao
     * @param EntityManager $em
     */
    public function __construct($wwwDir, $uploadDir, EntityDao $dao, EntityDao $fileDao, EntityManager $em)
    {
        parent::__construct($dao);

        $this->uploadDir = $wwwDir . $uploadDir;
        $this->fileDao   = $fileDao;
        $this->em        = $em;
    }

    public function uploadImages(
        Entities\TagEntity $tag,
        array $images,
        Entities\UserEntity $user
    ) {
        if ($images) {
            $fm = new FileManager($this->em, $this->fileDao, $this->uploadDir);

            foreach ($images as $img) {
                $e       = new Entities\ImageEntity;
                $e->file = $fm->upload(new Entities\FileEntity, $img);
                $e->user = $user;
                $e->tag  = $tag;

                $this->em->persist($e);
                $this->em->flush();
            }
        }
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $activeOnly = false)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('i')
            ->from(Entities\ImageEntity::getClassName(), 'i');

        if ($activeOnly) {
            $qb->where('i.isActive = :state')
                ->setParameter('state', true);
        }

        $qb->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('i')
            ->from(Entities\ImageEntity::getClassName(), 'i')
            ->join('i.tag', 't')
            ->where('t.id = :tagId');

        $params = array('tagId' => $tag->id);

        if ($activeOnly) {
            $qb->andWhere('i.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\ImageEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dao->createQueryBuilder()
            ->select('i')
            ->from(Entities\ImageEntity::getClassName(), 'i')
            ->join('i.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('i')
            ->from(Entities\ImageEntity::getClassName(), 'i')
            ->join('i.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
