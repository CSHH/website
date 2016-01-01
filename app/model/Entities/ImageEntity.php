<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="`image`")
 */
class ImageEntity extends BaseEntity
{
    use Identifier;
    use Timestampable;

    /**
     * @ORM\ManyToOne(targetEntity="TagEntity")
     *
     * @var TagEntity
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity")
     *
     * @var UserEntity
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="FileEntity")
     *
     * @var FileEntity
     */
    protected $file;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $isActive = false;
}
