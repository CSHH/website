<?php

namespace App\Repositories;

use App\Caching\MenuCache;
use App\Duplicities\DuplicityChecker;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Utils\HtmlPurifierFactory;
use App\Utils\PaginatorFactory;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HeavenProject\Utils\Slugger;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;

class ArticleRepository extends SingleUserContentRepository
{
    use DuplicityChecker;

    /** @var ITranslator */
    private $translator;

    /** @var \HtmlPurifier */
    private $htmlPurifier;

    public function __construct(
        EntityDao $dao,
        ITranslator $translator,
        EntityManager $em,
        MenuCache $menuCache
    ) {
        parent::__construct($dao, $em, $menuCache->setArticleRepository($this));

        $this->translator   = $translator;
        $this->htmlPurifier = (new HtmlPurifierFactory)->createHtmlPurifier();
    }

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

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    public function update(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\ArticleEntity $e
    ) {
        if ($e->tag->id !== $tag->id) {
            $this->menuCache->deleteSection(MenuCache::SECTION_ARTICLES);
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

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    /**
     * @param  Entities\BaseEntity $e
     * @return Entities\BaseEntity
     */
    public function activate(Entities\BaseEntity $e)
    {
        return $this->doActivate($e, MenuCache::SECTION_ARTICLES);
    }

    /**
     * @param  Entities\ArticleEntity $e
     */
    public function delete(Entities\ArticleEntity $e)
    {
        $this->removeAndFlush($this->em, $e);

        $this->menuCache->deleteSection(MenuCache::SECTION_ARTICLES);
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
        return $this->doGetAllForPage(Entities\ArticleEntity::getClassName(), $paginatorFactory, $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @return Entities\ArticleEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->doGetAllByTag(Entities\ArticleEntity::getClassName(), $tag);
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $name
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->doGetByTagAndName(Entities\ArticleEntity::getClassName(), $tag, $name);
    }

    /**
     * @param  Entities\TagEntity          $tag
     * @param  string                      $slug
     * @return Entities\ArticleEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->doGetByTagAndSlug(Entities\ArticleEntity::getClassName(), $tag, $slug);
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
        return $this->doGetAllByTagForPage(Entities\ArticleEntity::getClassName(), $paginatorFactory, $page, $limit, $tag, $activeOnly);
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
        return $this->doGetAllByUserForPage(Entities\ArticleEntity::getClassName(), $paginatorFactory, $page, $limit, $user);
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @return Paginator
     */
    public function getAllInactiveForPage(PaginatorFactory $paginatorFactory, $page, $limit)
    {
        return $this->doGetAllInactiveForPage(Entities\ArticleEntity::getClassName(), $paginatorFactory, $page, $limit);
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
        return $this->doGetAllInactiveByTagForPage(Entities\ArticleEntity::getClassName(), $paginatorFactory, $page, $limit, $tag);
    }

    /**
     * @return Entities\ArticleEntity[]
     */
    public function getAllNews()
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(Entities\ArticleEntity::getClassName(), 'e')
            ->join('e.tag', 't')
            ->where('e.isActive = :state AND t.id = :tagId')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(20)
            ->setParameters(array(
                'state' => true,
                'tagId' => Entities\TagEntity::NEWS_ID,
            ));

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
            ->from(Entities\ArticleEntity::getClassName(), 'e')
            ->join('e.tag', 't')
            ->where('e.isActive = :state AND t.id != :tagId')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->setParameters(array(
                'state' => true,
                'tagId' => Entities\TagEntity::NEWS_ID,
            ));

        return $qb->getQuery()
            ->getResult();
    }
}