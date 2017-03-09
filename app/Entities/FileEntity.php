<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HeavenProject\FileManagement\FileEntityInterface;
use HeavenProject\FileManagement\FileEntityTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="`file`")
 */
class FileEntity extends TimestampableEntity implements FileEntityInterface
{
    use FileEntityTrait;

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->year . '/' . $this->month . '/' . $this->name . '.' . $this->extension;
    }
}
