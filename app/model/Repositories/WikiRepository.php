<?php

namespace App\Model\Crud;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HeavenProject\Utils\Slugger;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;

class WikiCrud extends BaseCrud
{
    use DuplicityChecker;

    /** @var ITranslator */
    private $translator;

    /** @var EntityManager */
    private $em;

    public function __construct(
        EntityDao $dao,
        ITranslator $translator,
        EntityManager $em
    ) {
        parent::__construct($dao);

        $this->translator = $translator;
        $this->em         = $em;
    }

    /**
     * @param  ArrayHash $values
     * @param  Entities\TagEntity $tag
     * @param  Entities\UserEntity $user
     * @param  string $type
     * @param  Entities\WikiEntity $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\WikiEntity
     */
    public function create(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        $type,
        Entities\WikiEntity $e
    ) {
        $e->setValues($values);

        if ($this->getByTagAndNameAndType($tag, $values->name, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($this->getByTagAndSlugAndType($tag, $e->slug, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->tag       = $tag;
        $e->createdBy = $user;
        $e->type      = $type;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    /**
     * @param  ArrayHash $values
     * @param  Entities\TagEntity $tag
     * @param  Entities\UserEntity $user
     * @param  string $type
     * @param  Entities\WikiEntity $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\WikiEntity
     */
    public function update(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\WikiEntity $e
    ) {
        $e->setValues($values);

        if ($e->tag->id !== $tag->id && $this->getByTagAndNameAndType($tag, $values->name, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($e->tag->id !== $tag->id && $this->getByTagAndSlugAndType($tag, $e->slug, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->tag           = $tag;
        $e->lastUpdatedBy = $user;
        $e->type          = $type;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $type)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->where('w.type = :type')
            ->setParameter('type', $type)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  string             $type
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag, $type)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->join('w.tag', 't')
            ->where('t.id = :tagId AND w.type = :type')
            ->setParameters(array(
                'tagId' => $tag->id,
                'type'  => $type,
            ))
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
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.name = :name')
                ->setParameters(array(
                    'tagId' => $tag->id,
                    'name'  => $name,
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
     * @param  string                   $slug
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

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndNameAndType(Entities\TagEntity $tag, $name, $type)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.name = :name AND w.type = :type')
                ->setParameters(array(
                    'tagId' => $tag->id,
                    'name'  => $name,
                    'type'  => $type,
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
     * @param  string                   $slug
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlugAndType(Entities\TagEntity $tag, $slug, $type)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('w')
                ->from(Entities\WikiEntity::getClassName(), 'w')
                ->join('w.tag', 't')
                ->where('t.id = :tagId AND w.slug = :slug AND w.type = :type')
                ->setParameters(array(
                    'tagId' => $tag->id,
                    'slug'  => $slug,
                    'type'  => $type,
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
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @param  string              $type
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user, $type)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::getClassName(), 'w')
            ->join('w.createdBy', 'u')
            ->where('u.id = :userId AND w.type = :type')
            ->setParameters(array(
                'userId' => $user->id,
                'type'   => $type,
            ))
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
