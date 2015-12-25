<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`wiki_draft`")
 */
class WikiDraftEntity extends BaseEntity
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
	 * @ORM\ManyToOne(targetEntity="WikiEntity")
	 *
	 * @var WikiEntity
	 */
	protected $wiki;

	/**
	 * @ORM\ManyToOne(targetEntity="UserEntity")
	 *
	 * @var UserEntity
	 */
	protected $user;

	/**
	 * @ORM\Column(type="text")
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * @ORM\Column(type="datetime")
	 *
	 * @var \DateTime
	 */
	protected $createdAt;
}
