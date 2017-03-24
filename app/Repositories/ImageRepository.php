<?php

namespace App\Repositories;

use App\Caching\MenuCache;
use App\Entities;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HeavenProject\FileManagement\FileManager;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

class ImageRepository extends SingleUserContentRepository
{
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
     * @param MenuCache     $menuCache
     */
    public function __construct(
        $wwwDir,
        $uploadDir,
        EntityDao $dao,
        EntityDao $fileDao,
        EntityManager $em,
        MenuCache $menuCache
    ) {
        parent::__construct($dao, $em, $menuCache->setImageRepository($this));

        $this->uploadDir = $wwwDir . $uploadDir;
        $this->fileDao   = $fileDao;
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

                $this->persistAndFlush($this->em, $e);
            }
        }
    }

    /**
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    public function activate(Entities\BaseEntity $e)
    {
        return $this->doActivate($e, MenuCache::SECTION_IMAGES);
    }

    /**
     * @param Entities\ImageEntity $e
     */
    public function delete(Entities\ImageEntity $e)
    {
        $file    = $e->file;
        $e->file = null;

        $this->removeAndFlush($this->em, $e);

        $fm = new FileManager($this->em, $this->fileDao, $this->uploadDir);
        $fm->removeFile($file);

        $this->menuCache->deleteSection(MenuCache::SECTION_IMAGES);
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @param  bool             $activeOnly
     * @return Paginator
     */
    public function getAllForPage(PaginatorFactory $paginatorFactory, $page, $limit, $activeOnly = false)
    {
        return $this->doGetAllForPage(Entities\ImageEntity::getClassName(), $paginatorFactory, $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\ImageEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->doGetAllByTag(Entities\ImageEntity::getClassName(), $tag);
    }

    /**
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        return $this->doGetAllByTagForPage(Entities\ImageEntity::getClassName(), $paginatorFactory, $page, $limit, $tag, $activeOnly);
    }

    /**
     * @param  PaginatorFactory    $paginatorFactory
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\UserEntity $user)
    {
        return $this->doGetAllByUserForPage(Entities\ImageEntity::getClassName(), $paginatorFactory, $page, $limit, $user);
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @return Paginator
     */
    public function getAllInactiveForPage(PaginatorFactory $paginatorFactory, $page, $limit)
    {
        return $this->doGetAllInactiveForPage(Entities\ImageEntity::getClassName(), $paginatorFactory, $page, $limit);
    }

    /**
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag)
    {
        return $this->doGetAllInactiveByTagForPage(Entities\ImageEntity::getClassName(), $paginatorFactory, $page, $limit, $tag);
    }

    /**
     * @return Entities\ImageEntity[]
     */
    public function getLatestImages()
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(Entities\ImageEntity::getClassName(), 'e')
            ->where('e.isActive = :state')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(12)
            ->setParameter('state', true);

        return $qb->getQuery()
            ->getResult();
    }
}
