<?php

namespace HeavenProject\FileManagement;

use Doctrine\ORM\Mapping as ORM;

trait FileEntityTrait
{
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $year;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $month;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $extension;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    public $checksum;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $joints;

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $checksum
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

    /**
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @param string $joints
     */
    public function setJoints($joints)
    {
        $this->joints = $joints;
    }

    /**
     * @return int
     */
    public function getJoints()
    {
        return $this->joints;
    }
}
