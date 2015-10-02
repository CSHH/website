<?php

namespace App\Model\Entities;

use Kdyby;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BaseEntity extends Kdyby\Doctrine\Entities\BaseEntity
{
}
