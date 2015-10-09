<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`image`")
 */
class ImageEntity extends BaseEntity
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
     * @ORM\Column(type="string", nullable=true, unique=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $alt;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $file;
}
