<?php

namespace App\Model\Duplicities;

use App\Model\Entities\BaseEntity;
use Kdyby\Doctrine\EntityManager;

/**
 * Checks for duplicities for given value.
 */
trait DuplicityChecker
{
    /**
     * @param  EntityManager    $em
     * @param  string           $entity
     * @param  string           $attribute
     * @param  string           $value
     * @param  string           $locale
     * @return BaseEntity|FALSE if value if not a duplicate
     */
    public function isValueDuplicate(EntityManager $em, $entity, $attribute, $value, $locale = null)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from($entity, 'e');

        if (empty($locale)) {
            $qb->where('e.' . $attribute . ' = :' . $attribute);
            $qb->setParameter($attribute, $value);
        } else {
            $qb->leftJoin('e.translations', 't');
            $qb->where('t.' . $attribute . ' = :' . $attribute . ' AND t.locale = :locale');
            $qb->setParameters([$attribute => $value, 'locale' => $locale]);
        }

        $result = $qb->getQuery()->getResult();

        return !empty($result) ? $result[0] : false;
    }
}
