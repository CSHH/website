<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`image`")
 */
class ImageEntity extends TimestampableEntity
{
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
     * @ORM\ManyToOne(targetEntity="FileEntity", cascade={"persist", "remove"})
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
