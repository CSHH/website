<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table(name="`wiki`")
 */
class WikiEntity extends BaseEntity
{
    use Identifier;
    use Timestampable;

    /** @var string */
    const TYPE_GAME = 'game';
    /** @var string */
    const TYPE_MOVIE = 'movie';
    /** @var string */
    const TYPE_BOOK = 'book';

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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $type;

    public function __construct()
    {
        parent::__construct();

        $this->related      =
        $this->contributors =
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
    public function getDrafts()
    {
        return $this->drafts;
    }
}
