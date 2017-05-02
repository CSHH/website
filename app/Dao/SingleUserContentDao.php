<?php

namespace App\Dao;

use App\Entities;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\QueryBuilder;

class SingleUserContentDao
{
    /**
     * @param  EntityDao        $dao
     * @param  string           $className
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @param  bool             $activeOnly
     * @return Paginator
     */
    public function getAllForPage(EntityDao $dao, $className, PaginatorFactory $paginatorFactory, $page, $limit, $activeOnly = false)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e');

        if ($activeOnly) {
            $qb->where('e.isActive = :state')
                ->setParameter('state', true);
        }

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  EntityDao             $dao
     * @param  string                $className
     * @param  Entities\TagEntity    $tag
     * @return Entities\BaseEntity[]
     */
    public function getAllByTag(EntityDao $dao, $className, Entities\TagEntity $tag)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id);

        $this->orderByDesc($qb, 'e');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  EntityDao                $dao
     * @param  string                   $className
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\BaseEntity|null
     */
    public function getByTagAndName(EntityDao $dao, $className, Entities\TagEntity $tag, $name)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('e')
                ->from($className, 'e')
                ->join('e.tag', 't')
                ->where('t.id = :tagId AND e.name = :name')
                ->setParameters([
                    'tagId' => $tag->id,
                    'name'  => $name,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param  EntityDao                $dao
     * @param  string                   $className
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @return Entities\BaseEntity|null
     */
    public function getByTagAndSlug(EntityDao $dao, $className, Entities\TagEntity $tag, $slug)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('e')
                ->from($className, 'e')
                ->join('e.tag', 't')
                ->where('t.id = :tagId AND e.slug = :slug')
                ->setParameters([
                    'tagId' => $tag->id,
                    'slug'  => $slug,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param  EntityDao          $dao
     * @param  string             $className
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage(EntityDao $dao, $className, PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId');

        $params = ['tagId' => $tag->id];

        if ($activeOnly) {
            $qb->andWhere('e.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  EntityDao           $dao
     * @param  string              $className
     * @param  PaginatorFactory    $paginatorFactory
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage(EntityDao $dao, $className, PaginatorFactory $paginatorFactory, $page, $limit, Entities\UserEntity $user)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  EntityDao        $dao
     * @param  string           $className
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @return Paginator
     */
    public function getAllInactiveForPage(EntityDao $dao, $className, PaginatorFactory $paginatorFactory, $page, $limit)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->where('e.isActive = :state')
            ->setParameter('state', false);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  EntityDao          $dao
     * @param  string             $className
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage(EntityDao $dao, $className, PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag)
    {
        $qb = $dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId AND e.isActive = :state')
            ->setParameters([
                'tagId' => $tag->id,
                'state' => false,
            ]);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $page
     * @param int          $limit
     */
    private function preparePagination(QueryBuilder $qb, $page, $limit)
    {
        $qb->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $select
     */
    private function orderByDesc(QueryBuilder $qb, $select)
    {
        $qb->orderBy($select . '.createdAt', 'DESC');
    }
}
