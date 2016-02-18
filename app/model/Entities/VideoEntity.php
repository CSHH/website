<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`video`")
 */
class VideoEntity extends TimestampableEntity
{
    /** @var string */
    const TYPE_YOUTUBE = 'youtube';
    /** @var string */
    const TYPE_VIMEO = 'vimeo';

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
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $url;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $src;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $isActive = false;
}
