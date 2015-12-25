<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BaseEntity extends Kdyby\Doctrine\Entities\BaseEntity
{
}
