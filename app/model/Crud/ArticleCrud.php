<?php

namespace App\Model\Crud;

use App\Model\Entities;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;

class ArticleCrud extends BaseCrud
{
    public function __construct(EntityDao $dao)
    {
        parent::__construct($dao);
    }

	/**
	 * @param int $page
	 * @param int $limit
	 * @return Paginator
	 */
	public function getAllForPage($page, $limit)
	{
		$qb = $this->dao->createQueryBuilder()
			->select('a')
			->from(Entities\ArticleEntity::getClassName(), 'a')
			->setFirstResult($page * $limit - $limit)
			->setMaxResults($limit);

		return new Paginator($qb->getQuery());
	}

    /**
	 * @param int $page
	 * @param int $limit
     * @param string $tag
	 * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, $tag)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.tag', 't')
            ->where('t.slug = :tag')
            ->setParameter('tag', $tag)
			->setFirstResult($page * $limit - $limit)
			->setMaxResults($limit);

		return new Paginator($qb->getQuery());
    }

    /**
     * @param  string                   $tag
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTag($tag)
    {
        return $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.tag', 't')
            ->where('t.slug = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  string                      $tag
     * @param  string                      $slug
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndSlug($tag, $slug)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('a')
                ->from(Entities\ArticleEntity::getClassName(), 'a')
                ->join('a.tag', 't')
                ->where('t.slug = :tag AND a.slug = :slug')
                ->setParameters(
                    array(
                        'tag'  => $tag,
                        'slug' => $slug,
                    )
                )
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }
}
