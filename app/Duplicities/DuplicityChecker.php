<?php

namespace App\Duplicities;

use App\Entities\BaseEntity;
use Kdyby\Doctrine\EntityManager;

class DuplicityChecker
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param  string           $entityClassName
     * @param  string           $attribute
     * @param  string           $value
     * @return BaseEntity|false
     */
    public function isValueDuplicate($entityClassName, $attribute, $value)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($entityClassName, 'e');
        $qb->where('e.' . $attribute . ' = :' . $attribute);
        $qb->setParameter($attribute, $value);

        $result = $qb->getQuery()->getResult();

        return !empty($result) ? $result[0] : false;
    }
}
