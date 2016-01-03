<?php

namespace App\Model\Crud;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\ArrayHash;

class ArticleCrud extends BaseCrud
{
    use DuplicityChecker;

    /** @var EntityManager */
    private $em;

    public function __construct(EntityDao $dao, EntityManager $em)
    {
        parent::__construct($dao);

        $this->em = $em;
    }

    public function create(
        ArrayHash $values,
        Entities\TagEntity $tag
    ) {
        $e = new Entities\ArticleEntity;

        $ent = $this->isValueDuplicate($this->em, Entities\ArticleEntity::getClassName(), 'name', $values->name);
        if ($ent) {
            throw new PossibleUniqueKeyDuplicationException('Článek s tímto názvem již existuje.');
        }

        $e->tag = $tag;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    public function update(
        ArrayHash $values,
        Entities\ArticleEntity $e,
        Entities\TagEntity $tag
    ) {
        $e->setValues($values);

        $ent = $this->isValueDuplicate($this->em, Entities\ArticleEntity::getClassName(), 'name', $values->name);
        if ($ent && $ent->id !== $e->id) {
            throw new PossibleUniqueKeyDuplicationException('Článek s tímto názvem již existuje.');
        }

        $e->tag = $tag;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    /**
     * @param  int       $page
     * @param  int       $limit
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
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $slug
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('a')
                ->from(Entities\ArticleEntity::getClassName(), 'a')
                ->join('a.tag', 't')
                ->where('t.id = :tagId AND a.slug = :slug')
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

    /**
     * @param  Entities\TagEntity       $tag
     * @param  Entities\UserEntity      $user
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTagAndUser(Entities\TagEntity $tag, Entities\UserEntity $user)
    {
        return $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.tag', 't')
            ->join('a.user', 'u')
            ->where('t.id = :tagId AND u.id = :userId')
            ->setParameters(array(
                'tagId'  => $tag->id,
                'userId' => $user->id,
            ))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  Entities\UserEntity      $user
     * @return Entities\ArticleEntity[]
     */
    public function getAllByUser(Entities\UserEntity $user)
    {
        return $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('a')
            ->from(Entities\ArticleEntity::getClassName(), 'a')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
