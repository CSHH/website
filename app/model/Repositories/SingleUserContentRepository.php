<?php

namespace App\Model\Repositories;

use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

abstract class SingleUserContentRepository extends BaseRepository
{
    /** @var EntityManager */
    protected $em;

    public function __construct(
        EntityDao $dao,
        EntityManager $em
    ) {
        parent::__construct($dao);

        $this->em = $em;
    }

    /**
     * @param  string    $className
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    protected function doGetAllForPage($className, $page, $limit, $activeOnly = false)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e');

        if ($activeOnly) {
            $qb->where('e.isActive = :state')
                ->setParameter('state', true);
        }

        $qb->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  string                   $className
     * @param  Entities\TagEntity       $tag
     * @return Entities\ArticleEntity[]
     */
    protected function doGetAllByTag($className, Entities\TagEntity $tag)
    {
        return $this->dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  string             $className
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    protected function doGetAllByTagForPage($className, $page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.tag', 't')
            ->where('t.id = :tagId');

        $params = array('tagId' => $tag->id);

        if ($activeOnly) {
            $qb->andWhere('e.isActive = :state');
            $params['state'] = true;
        }

        $qb->setParameters($params)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  string              $className
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    protected function doGetAllByUserForPage($className, $page, $limit, Entities\UserEntity $user)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from($className, 'e')
            ->join('e.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
