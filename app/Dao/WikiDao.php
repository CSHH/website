<?php

namespace App\Dao;

use App\Entities;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryBuilder;

class WikiDao
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
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $type, $activeOnly = false)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->where('w.type = :type');

        $params = ['type' => $type];

        if ($activeOnly) {
            $qb->andWhere('w.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity    $tag
     * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag, $type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.type = :type')
            ->setParameters([
                'tagId' => $tag->id,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name, $type)
    {
        try {
            return $this->em->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::class, 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.name = :name AND w.type = :type')
                ->setParameters([
                    'tagId' => $tag->id,
                    'name'  => $name,
                    'type'  => $type,
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
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug, $type)
    {
        try {
            return $this->em->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::class, 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.slug = :slug AND w.type = :type')
                ->setParameters([
                    'tagId' => $tag->id,
                    'slug'  => $slug,
                    'type'  => $type,
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
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  string             $type
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag, $type, $activeOnly = false)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.type = :type');

        $params = [
            'tagId' => $tag->id,
            'type'  => $type,
        ];

        if ($activeOnly) {
            $qb->andWhere('w.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @param  string              $type
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user, $type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->join('w.createdBy', 'u')
            ->where('u.id = :userId AND w.type = :type')
            ->setParameters([
                'userId' => $user->id,
                'type'   => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllActive($type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->where('w.isActive = :state AND w.type = :type')
            ->setParameters([
                'state' => true,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllInactive($type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->where('w.isActive = :state AND w.type = :type')
            ->setParameters([
                'state' => false,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit, $type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->where('w.isActive = :state AND w.type = :type')
            ->setParameters([
                'state' => false,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  string             $type
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($page, $limit, Entities\TagEntity $tag, $type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.isActive = :state AND w.type = :type')
            ->setParameters([
                'tagId' => $tag->id,
                'state' => false,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return $this->paginatorFactory->createPaginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity $tag
     * @param  string $type
     * @return Entities\BaseEntity[]
     */
    public function getAllActiveByTag(Entities\TagEntity $tag, $type)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.isActive = :state AND w.type = :type')
            ->setParameters([
                'tagId' => $tag->id,
                'state' => true,
                'type'  => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param  string $type
     * @return Entities\TagEntity[]
     */
    public function getAllTags($type)
    {
        $tags = [];
        foreach ($this->getAllActive($type) as $entity) {
            $id  = $entity->tag->id;
            $tag = $entity->tag;
            if (array_key_exists($id, $tags) === false) {
                $tags[$id] = $tag;
            }
        }

        return $tags;
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
