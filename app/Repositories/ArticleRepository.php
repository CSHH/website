<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\SingleUserContentDao;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HeavenProject\Utils\Slugger;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;

class ArticleRepository extends SingleUserContentRepository
{
    /** @var ITranslator */
    private $translator;

    /** @var \HtmlPurifier */
    private $htmlPurifier;

    public function __construct(
        EntityDao $dao,
        SingleUserContentDao $dataAccess,
        ITranslator $translator,
        EntityManager $em,
        Caching\ArticleTagSectionCache $tagCache,
        \HTMLPurifier $htmlPurifier
    ) {
        parent::__construct($dao, $dataAccess, $em, $tagCache);

        $this->translator   = $translator;
        $this->htmlPurifier = $htmlPurifier;
    }

    /**
     * @param  ArrayHash                             $values
     * @param  Entities\TagEntity                    $tag
     * @param  Entities\UserEntity                   $user
     * @param  Entities\ArticleEntity                $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\ArticleEntity
     */
    public function create(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\ArticleEntity $e
    ) {
        $e->setValues($values);

        if ($this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->text = $this->htmlPurifier->purify($values->text);
        $e->tag  = $tag;
        $e->user = $user;

        return $this->persistAndFlush($this->em, $e);
    }

    /**
     * @param  ArrayHash                             $values
     * @param  Entities\TagEntity                    $tag
     * @param  Entities\UserEntity                   $user
     * @param  Entities\ArticleEntity                $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\ArticleEntity
     */
    public function update(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\ArticleEntity $e
    ) {
        if ($e->tag->id !== $tag->id) {
            $this->tagCache->deleteSection();
        }

        $e->setValues($values);

        if ($e->tag->id !== $tag->id && $this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($e->tag->id !== $tag->id && $this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->text = $this->htmlPurifier->purify($values->text);
        $e->tag  = $tag;
        $e->user = $user;

        return $this->persistAndFlush($this->em, $e);
    }

    /**
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    public function activate(Entities\BaseEntity $e)
    {
        return $this->doActivate($e);
    }

    /**
     * @param  Entities\ArticleEntity $e
     * @return Entities\ArticleEntity
     */
    public function delete(Entities\ArticleEntity $e)
    {
        $ent = $this->removeAndFlush($this->em, $e);

        $this->tagCache->deleteSection();

        return $ent;
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @param  bool             $activeOnly
     * @return Paginator
     */
    public function getAllForPage(PaginatorFactory $paginatorFactory, $page, $limit, $activeOnly = false)
    {
        return $this->dataAccess->getAllForPage(Entities\ArticleEntity::class, $paginatorFactory, $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag(Entities\ArticleEntity::class, $tag);
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $name
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->dataAccess->getByTagAndName(Entities\ArticleEntity::class, $tag, $name);
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $slug
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->dataAccess->getByTagAndSlug(Entities\ArticleEntity::class, $tag, $slug);
    }

    /**
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        return $this->dataAccess->getAllByTagForPage(Entities\ArticleEntity::class, $paginatorFactory, $page, $limit, $tag, $activeOnly);
    }

    /**
     * @param  PaginatorFactory    $paginatorFactory
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\UserEntity $user)
    {
        return $this->dataAccess->getAllByUserForPage(Entities\ArticleEntity::class, $paginatorFactory, $page, $limit, $user);
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @return Paginator
     */
    public function getAllInactiveForPage(PaginatorFactory $paginatorFactory, $page, $limit)
    {
        return $this->dataAccess->getAllInactiveForPage(Entities\ArticleEntity::class, $paginatorFactory, $page, $limit);
    }

    /**
     * @param  PaginatorFactory   $paginatorFactory
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage(PaginatorFactory $paginatorFactory, $page, $limit, Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllInactiveByTagForPage(Entities\ArticleEntity::class, $paginatorFactory, $page, $limit, $tag);
    }

    /**
     * @return Entities\ArticleEntity[]
     */
    public function getAllNews()
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(Entities\ArticleEntity::class, 'e')
            ->join('e.tag', 't')
            ->where('e.isActive = :state AND t.id = :tagId')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(20)
            ->setParameters([
                'state' => true,
                'tagId' => Entities\TagEntity::NEWS_ID,
            ]);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return Entities\ArticleEntity[]
     */
    public function getLatestArticles()
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(Entities\ArticleEntity::class, 'e')
            ->join('e.tag', 't')
            ->where('e.isActive = :state AND t.id != :tagId')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->setParameters([
                'state' => true,
                'tagId' => Entities\TagEntity::NEWS_ID,
            ]);

        return $qb->getQuery()
            ->getResult();
    }
}
