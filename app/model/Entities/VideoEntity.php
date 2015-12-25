<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="`video`")
 */
class VideoEntity extends BaseEntity
{
    use Identifier;
    use Timestampable;

	/** @var string */
	const TYPE_YOUTUBE = 'youtube';
	/** @var string */
	const TYPE_VIMEO = 'vimeo';

	/**
	 * @ORM\ManyToMany(targetEntity="TagEntity")
	 * @ORM\JoinTable(
	 *     name="video_tag",
	 *     joinColumns={
	 *         @ORM\JoinColumn(name="video_id", referencedColumnName="id")
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
     * @ORM\Column(type="string")
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
