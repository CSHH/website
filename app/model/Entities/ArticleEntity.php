<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="`article`")
 */
class ArticleEntity extends BaseEntity
{
    use Identifier;
    use Timestampable;

	/**
	 * @ORM\ManyToMany(targetEntity="TagEntity")
	 * @ORM\JoinTable(
	 *     name="article_tag",
	 *     joinColumns={
	 *         @ORM\JoinColumn(name="article_id", referencedColumnName="id")
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
    protected $perex;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $text;

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
