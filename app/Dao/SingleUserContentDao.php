<?php

namespace App\Dao;

use App\Entities;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryBuilder;

class SingleUserContentDao
{
    /** @var EntityManager */
    private $em;

    /** @var PaginatorFactory */
    private $paginatorFactory;

    public function __construct(EntityManager $em, PaginatorFactory $paginatorFactory)
    {
        $this->em               = $em;
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * @param  string    $className
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($className, $page, $limit, $activeOnly = false)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e');

        if ($activeOnly) {
            $qb->where('e.isActive = :state')
                ->setParameter('state', true);
        }

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string                $className
     * @param  Entities\TagEntity    $tag
     * @return Entities\BaseEntity[]
     */
    public function getAllByTag($className, Entities\TagEntity $tag)
    {
        $qb = $this->em->createQueryBuilder()
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
     * @param  string                   $className
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\BaseEntity|null
     */
    public function getByTagAndName($className, Entities\TagEntity $tag, $name)
    {
        try {
            return $this->em->createQueryBuilder()
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
     * @param  string                   $className
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @return Entities\BaseEntity|null
     */
    public function getByTagAndSlug($className, Entities\TagEntity $tag, $slug)
    {
        try {
            return $this->em->createQueryBuilder()
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
     * @param  string             $className
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage($className, $page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        $qb = $this->em->createQueryBuilder()
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

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string              $className
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($className, $page, $limit, Entities\UserEntity $user)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string                $className
     * @return Entities\BaseEntity[]
     */
    public function getAllActive($className)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->where('e.isActive = :state')
            ->setParameter('state', true);

        $this->orderByDesc($qb, 'e');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  string                $className
     * @return Entities\BaseEntity[]
     */
    public function getAllInactive($className)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->where('e.isActive = :state')
            ->setParameter('state', false);

        $this->orderByDesc($qb, 'e');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  string    $className
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllInactiveForPage($className, $page, $limit)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->where('e.isActive = :state')
            ->setParameter('state', false);

        $this->orderByDesc($qb, 'e');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string             $className
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($className, $page, $limit, Entities\TagEntity $tag)
    {
        $qb = $this->em->createQueryBuilder()
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

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string $className
     * @param  Entities\TagEntity $tag
     * @return Entities\BaseEntity[]
     */
    public function getAllActiveByTag($className, Entities\TagEntity $tag)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId AND e.isActive = :state')
            ->setParameters([
                'tagId' => $tag->id,
                'state' => true,
            ]);

        $this->orderByDesc($qb, 'e');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  string $className
     * @return Entities\TagEntity[]
     */
    public function getAllTags($className)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('t')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('e.isActive = :state')
            ->setParameter('state', true)
            ->groupBy('t.id');

        return $qb->getQuery()
            ->getResult();
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
