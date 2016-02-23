<?php

namespace App\Model\Repositories;

use App\Model\Entities;
use Kdyby\Doctrine\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use HeavenProject\FileManagement\FileManager;

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
     */
    public function __construct($wwwDir, $uploadDir, EntityDao $dao, EntityDao $fileDao, EntityManager $em)
    {
        parent::__construct($dao, $em);

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
     * @param  Entities\ImageEntity $e
     */
    public function delete(Entities\ImageEntity $e)
    {
        $file    = $e->file;
        $e->file = null;

        $this->removeAndFlush($this->em, $e);

        $fm = new FileManager($this->em, $this->fileDao, $this->uploadDir);
        $fm->removeFile($file);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $activeOnly = false)
    {
        return $this->doGetAllForPage(Entities\ImageEntity::getClassName(), $page, $limit, $activeOnly);
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
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        return $this->doGetAllByTagForPage(Entities\ImageEntity::getClassName(), $page, $limit, $tag, $activeOnly);
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        return $this->doGetAllByUserForPage(Entities\ImageEntity::getClassName(), $page, $limit, $user);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit)
    {
        return $this->doGetAllInactiveForPage(Entities\ImageEntity::getClassName(), $page, $limit);
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        return $this->doGetAllInactiveByTagForPage(Entities\ImageEntity::getClassName(), $page, $limit, $tag);
    }
}
