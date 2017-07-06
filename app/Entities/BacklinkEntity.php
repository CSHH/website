<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`backlink`")
 */
class BacklinkEntity extends TimestampableEntity
{
    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $oldPath;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $newPath;
}
