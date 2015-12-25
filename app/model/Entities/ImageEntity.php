<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="TagEntity")
     * @ORM\JoinTable(
     *     name="image_tag",
     *     joinColumns={
     *         @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $tags;

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

    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
