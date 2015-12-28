<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use HeavenProject\FileManagement\FileEntityInterface;
use HeavenProject\FileManagement\FileEntityTrait;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="`file`")
 */
class FileEntity extends BaseEntity implements FileEntityInterface
{
    use Identifier;
    use FileEntityTrait;
    use Timestampable;

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->year . '/' . $this->month . '/' . $this->name . '.' . $this->extension;
    }
}
