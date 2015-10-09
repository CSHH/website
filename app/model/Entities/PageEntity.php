<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`page`")
 */
class PageEntity extends BaseEntity
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
    protected $title;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $keywords;

    /**
     * @ORM\ManyToMany(targetEntity="TagEntity")
     * @ORM\JoinTable(name="page_tag",
     *     joinColumns={
     *         @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    protected $tags;

    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection;
    }
}
