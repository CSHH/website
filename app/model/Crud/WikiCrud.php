<?php

namespace App\Model\Crud;

use App\Model\Entities;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;

class WikiCrud extends BaseCrud
{
    public function __construct(EntityDao $dao)
    {
        parent::__construct($dao);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllForPage($page, $limit)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity    $tag
	 * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag, $type)
    {
        return $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.type = :type')
            ->setParameters(array(
				'tagId' => $tag->id,
				'type'  => $type,
			))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $slug
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.slug = :slug')
                ->setParameters(array(
                    'tagId' => $tag->id,
                    'slug'  => $slug,
                ))
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }
}
