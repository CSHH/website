<?php

namespace App\Repositories;

use App\Entities;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryBuilder;

abstract class BaseRepository
{
    /** @var EntityDao */
    protected $dao;

    public function __construct(EntityDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param  array                 $criteria
     * @param  array                 $orderBy
     * @param  int                   $limit
     * @param  int                   $offset
     * @return Entities\BaseEntity[]
     */
    public function getAll(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->dao->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param  int                 $id
     * @return Entities\BaseEntity
     */
    public function getById($id)
    {
        return $this->dao->find($id);
    }

    /**
     * @param  array $criteria
     * @return int
     */
    public function getCount(array $criteria = [])
    {
        return (int) $this->dao->countBy($criteria);
    }

    /**
     * @param  EntityManager       $em
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    protected function persistAndFlush(EntityManager $em, Entities\BaseEntity $e)
    {
        $em->persist($e);
        $em->flush();
        return $e;
    }

    /**
     * @param  EntityManager       $em
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    protected function removeAndFlush(EntityManager $em, Entities\BaseEntity $e)
    {
        $em->remove($e);
        $em->flush();
        return $e;
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $page
     * @param int          $limit
     */
    protected function preparePagination(QueryBuilder $qb, $page, $limit)
    {
        $qb->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);
    }
}
