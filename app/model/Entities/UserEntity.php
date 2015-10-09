<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class UserEntity extends BaseEntity
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
	 * @ORM\Column(type="string", unique=true, nullable=true)
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string", unique=true, nullable=true)
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 *
	 * @var string
	 */
	protected $avatar;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 *
	 * @var string
	 */
	protected $forename;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 *
	 * @var string
	 */
	protected $surname;
}
