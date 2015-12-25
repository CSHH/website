<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`image`")
 */
class ImageEntity extends BaseEntity
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
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $alt;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $file;

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
