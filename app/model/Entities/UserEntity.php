<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class UserEntity extends TimestampableEntity
{
    /** @var int */
    const ROLE_ADMINISTRATOR = 99;
    /** @var int */
    const ROLE_USER = 1;

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
     * @ORM\ManyToOne(targetEntity="FileEntity")
     *
     * @var FileEntity
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

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, unique=true)
     *
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $role = self::ROLE_USER;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $tokenCreatedAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $isAuthenticated = false;
}
