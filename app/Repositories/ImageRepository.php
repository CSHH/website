<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\SingleUserContentDao;
use App\Entities;
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
     * @param string                       $wwwDir
     * @param string                       $uploadDir
     * @param EntityDao                    $dao
     * @param EntityDao                    $fileDao
     * @param SingleUserContentDao         $dataAccess
     * @param EntityManager                $em
     * @param Caching\ImageTagSectionCache $tagCache
     */
    public function __construct(
        $wwwDir,
        $uploadDir,
        EntityDao $dao,
        EntityDao $fileDao,
        SingleUserContentDao $dataAccess,
        EntityManager $em,
        Caching\ImageTagSectionCache $tagCache
    ) {
        parent::__construct($dao, $dataAccess, $em, $tagCache);

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
        return $this->doActivate($e);
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

        $this->tagCache->deleteSection();
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $activeOnly = false)
    {
        return $this->dataAccess->getAllForPage(Entities\ImageEntity::class, $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\ImageEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag(Entities\ImageEntity::class, $tag);
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
        return $this->dataAccess->getAllByTagForPage(Entities\ImageEntity::class, $page, $limit, $tag, $activeOnly);
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        return $this->dataAccess->getAllByUserForPage(Entities\ImageEntity::class, $page, $limit, $user);
    }

    /**
     * @return Entities\ImageEntity[]
     */
    public function getAllActive()
    {
        return $this->dataAccess->getAllActive(Entities\ImageEntity::class);
    }

    /**
     * @return Entities\ImageEntity[]
     */
    public function getAllInactive()
    {
        return $this->dataAccess->getAllInactive(Entities\ImageEntity::class);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit)
    {
        return $this->dataAccess->getAllInactiveForPage(Entities\ImageEntity::class, $page, $limit);
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllInactiveByTagForPage(Entities\ImageEntity::class, $page, $limit, $tag);
    }

    /**
     * @param  Entities\TagEntity $tag
     * @return Entities\ImageEntity[]
     */
    public function getAllActiveByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllActiveByTag(Entities\ImageEntity::class, $tag);
    }

    /**
     * @return Entities\TagEntity[]
     */
    public function getAllTags()
    {
        return $this->dataAccess->getAllTags(Entities\ImageEntity::class);
    }
}
