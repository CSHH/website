<?php

namespace App\Dao;

use App\Entities;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\QueryBuilder;

class WikiDao
{
    /**
     * @param  EntityDao $dao
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage(EntityDao $dao, $page, $limit, $type, $activeOnly = false)
    {
        $qb = $dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->where('w.type = :type');

        $params = ['type' => $type];

        if ($activeOnly) {
            $qb->andWhere('w.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  EntityDao             $dao
     * @param  Entities\TagEntity    $tag
     * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(EntityDao $dao, Entities\TagEntity $tag, $type)
    {
        $qb = $dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
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
     * @param  EntityDao                $dao
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndName(EntityDao $dao, Entities\TagEntity $tag, $name)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.name = :name')
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
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlug(EntityDao $dao, Entities\TagEntity $tag, $slug)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.slug = :slug')
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
     * @param  EntityDao                $dao
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndNameAndType(EntityDao $dao, Entities\TagEntity $tag, $name, $type)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
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
     * @param  EntityDao                $dao
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlugAndType(EntityDao $dao, Entities\TagEntity $tag, $slug, $type)
    {
        try {
            return $dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
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
     * @param  EntityDao          $dao
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  string             $type
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage(EntityDao $dao, $page, $limit, Entities\TagEntity $tag, $type, $activeOnly = false)
    {
        $qb = $dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
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

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  EntityDao           $dao
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @param  string              $type
     * @return Paginator
     */
    public function getAllByUserForPage(EntityDao $dao, $page, $limit, Entities\UserEntity $user, $type)
    {
        $qb = $dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->join('w.createdBy', 'u')
            ->where('u.id = :userId AND w.type = :type')
            ->setParameters([
                'userId' => $user->id,
                'type'   => $type,
            ]);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return new Paginator($qb->getQuery());
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
