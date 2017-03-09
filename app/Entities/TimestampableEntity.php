<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\MappedSuperclass()
 */
abstract class TimestampableEntity extends BaseEntity
{
    use Timestampable;
}
