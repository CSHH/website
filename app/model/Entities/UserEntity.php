<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use HeavenProject\UserCommandLine\UserEntityInterface;
use HeavenProject\UserCommandLine\UserMethods;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class UserEntity extends TimestampableEntity implements UserEntityInterface
{
    use UserMethods;

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

    /**
     * @param string $username
     */
    public function setUsername($username = null)
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email = null)
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password = null)
    {
        $this->password = $password;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt = null)
    {
        $this->salt = $salt;
    }

    /**
     * @param string $token
     */
    public function setToken($token = null)
    {
        $this->token = $token;
    }

    /**
     * @param \DateTime $tokenCreatedAt
     */
    public function setTokenCreatedAt(\DateTime $tokenCreatedAt = null)
    {
        $this->tokenCreatedAt = $tokenCreatedAt;
    }

    /**
     * @param bool $isAuthenticated
     */
    public function setIsAuthenticated($isAuthenticated = false)
    {
        $this->isAuthenticated = $isAuthenticated;
    }
}
