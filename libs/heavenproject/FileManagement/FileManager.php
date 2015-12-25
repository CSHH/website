<?php

namespace HeavenProject\FileManagement;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;
use Nette\Utils\Strings;

class FileManager extends Nette\Object
{
    /** @var EntityManager */
    private $em;

    /** @var EntityDao */
    private $storageDao;

    /** @var string */
    private $uploadDir;

    /**
     * @param EntityManager $em
     * @param EntityDao     $storageDao
     * @param string        $uploadDir
     */
    public function __construct(EntityManager $em, EntityDao $storageDao, $uploadDir)
    {
        $this->em = $em;
        $this->storageDao = $storageDao;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param FileEntityInterface $entity
     */
    public function removeFile(FileEntityInterface $entity)
    {
        --$entity->joints;
        if ($entity->joints === 0) {
            $this->em->remove($entity);
            $this->em->flush();
            $this->unlinkFile($this->uploadDir.'/'.$entity->year.'/'.$entity->month.'/'.$entity->name.'.'.$entity->extension);
        } else {
            $this->em->persist($entity);
            $this->em->flush();
        }
    }

    /**
     * @param string $file
     */
    public function unlinkFile($file)
    {
        if (empty($file) && !is_file($file)) {
            return;
        }
        FileSystem::delete($file);

        $dir1 = dirname($file);
        $isEmpty = !(new \FilesystemIterator($dir1))->valid();
        if (!$isEmpty) {
            return;
        }
        FileSystem::delete($dir1);

        $dir2 = dirname($dir1);
        $isEmpty = !(new \FilesystemIterator($dir2))->valid();
        if (!$isEmpty) {
            return;
        }
        FileSystem::delete($dir2);

        $dir3 = dirname($dir2);
        $isEmpty = !(new \FilesystemIterator($dir3))->valid();
        if (!$isEmpty) {
            return;
        }
        FileSystem::delete($dir3);
    }

    /**
     * @param FileEntityInterface $entity
     * @param FileUpload          $file
     *
     * @return FileEntityInterface
     */
    public function upload(FileEntityInterface $entity, FileUpload $file)
    {
        $checksum = sha1_file($file->getTemporaryFile());
        $pairs = $this->storageDao->findPairs([], 'checksum', [], 'id');

        if (in_array($checksum, $pairs)) {
            $id = array_search($checksum, $pairs);
            $e = $this->storageDao->find($id);
            ++$e->joints;

            return $e;
        } else {
            $e = $entity;

            $y = date('Y');
            $m = date('m');

            $data = $this->getFileData($file);
            $file->move($this->uploadDir.'/'.$y.'/'.$m.'/'.$data->name.'.'.$data->extension);

            $e->name = $data->name;
            $e->extension = $data->extension;
            $e->year = $y;
            $e->month = $m;
            $e->checksum = $checksum;
            $e->joints = 1;

            return $e;
        }
    }

    /**
     * @param FileUpload $file
     *
     * @return ArrayHash
     */
    private function getFileData(FileUpload $file)
    {
        $name = $file->getSanitizedName();
        $extension = '';

        if (Strings::contains($name, '.')) {
            $fragments = explode('.', $name);
            array_shift($fragments);
            $extension = implode('.', $fragments);
        }

        $pairs = $this->storageDao->findPairs([], 'name', [], 'id');
        $data = new ArrayHash();

        while (true) {
            $name = Random::generate(10, '0-9A-Za-z');
            if (!in_array($name, $pairs)) {
                $data->name = $name;
                $data->extension = $extension;
                break;
            }
        }

        return $data;
    }
}
