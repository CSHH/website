<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`gallery`")
 */
class GalleryEntity extends BaseEntity
{
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="ImageEntity")
     * @ORM\JoinTable(name="gallery_image",
     *     joinColumns={
     *         @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $images;

    public function __construct()
    {
        parent::__construct();
        $this->images = new ArrayCollection;
    }
}
