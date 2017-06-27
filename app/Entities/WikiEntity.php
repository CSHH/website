<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`wiki`")
 */
class WikiEntity extends TimestampableEntity
{
    /** @var int */
    const TYPE_GAME = 1;
    /** @var int */
    const TYPE_MOVIE = 2;
    /** @var int */
    const TYPE_BOOK = 3;

    /**
     * @ORM\ManyToOne(targetEntity="TagEntity")
     *
     * @var TagEntity
     */
    protected $tag;

    /**
     * @ORM\ManyToMany(targetEntity="WikiEntity")
     * @ORM\JoinTable(
     *     name="wiki_related",
     *     joinColumns={
     *         @ORM\JoinColumn(name="wiki_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="related_wiki_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $related;

    /**
     * @ORM\ManyToMany(targetEntity="UserEntity")
     * @ORM\JoinTable(
     *     name="wiki_contributor",
     *     joinColumns={
     *         @ORM\JoinColumn(name="wiki_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $contributors;

    /**
     * @ORM\OneToMany(targetEntity="WikiDraftEntity", mappedBy="wiki")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     *
     * @var ArrayCollection
     */
    protected $drafts;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity")
     *
     * @var UserEntity
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity")
     *
     * @var UserEntity
     */
    protected $lastUpdatedBy;

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
     * @ORM\Column(type="integer")
     *
     * @var int
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

        $this->related      = new ArrayCollection;
        $this->contributors = new ArrayCollection;
        $this->drafts       = new ArrayCollection;
    }

    /**
     * @return ArrayCollection
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @return ArrayCollection
     */
    public function getContributors()
    {
        return $this->contributors;
    }

    /**
     * @return ArrayCollection
     */
    public function getContributorsWithAuthor()
    {
        $contributors = $this->contributors->toArray();
        array_unshift($contributors, $this->createdBy);

        return new ArrayCollection($contributors);
    }

    /**
     * @return ArrayCollection
     */
    public function getDrafts()
    {
        return $this->drafts;
    }
}
