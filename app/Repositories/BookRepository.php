<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\WikiDao;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

class BookRepository extends WikiRepository
{
    /** @var ITranslator */
    private $translator;

    /** @var \HtmlPurifier */
    private $htmlPurifier;

    public function __construct(
        EntityDao $dao,
        WikiDao $dataAccess,
        ITranslator $translator,
        EntityManager $em,
        Caching\BookTagSectionCache $tagCache,
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
     * @param  string                                $type
     * @param  Entities\WikiEntity                   $e
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

        if ($this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Strings::webalize($e->name);

        if ($this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->text      = $this->htmlPurifier->purify($values->text);
        $e->createdBy = $user;
        $e->tag       = $tag;
        $e->type      = $type;

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    /**
     * @param  ArrayHash                             $values
     * @param  Entities\TagEntity                    $tag
     * @param  string                                $type
     * @param  Entities\WikiEntity                   $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\WikiEntity
     */
    public function update(
        ArrayHash $values,
        Entities\TagEntity $tag,
        $type,
        Entities\WikiEntity $e
    ) {
        $e->setValues($values);

        if ($e->tag->id !== $tag->id && $this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Strings::webalize($e->name);

        if ($e->tag->id !== $tag->id && $this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }

        $e->text = $this->htmlPurifier->purify($values->text);
        $e->tag  = $tag;
        $e->type = $type;

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    /**
     * @param  Entities\WikiEntity $e
     * @return Entities\WikiEntity
     */
    public function activate(Entities\WikiEntity $e)
    {
        return $this->doActivate($e);
    }

    /**
     * @param  Entities\WikiEntity $e
     * @return Entities\WikiEntity
     */
    public function delete(Entities\WikiEntity $e)
    {
        $ent = $this->removeAndFlush($this->em, $e);

        $this->tagCache->deleteSection();

        return $ent;
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $activeOnly = false)
    {
        return $this->dataAccess->getAllForPage($page, $limit, Entities\WikiEntity::TYPE_BOOK, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity    $tag
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag($tag, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->dataAccess->getByTagAndName($tag, $name, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->dataAccess->getByTagAndSlug($tag, $slug, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @param  bool               $activeOnly
     * @return Paginator
     */
    public function getAllByTagForPage($page, $limit, Entities\TagEntity $tag, $activeOnly = false)
    {
        return $this->dataAccess->getAllByTagForPage($page, $limit, $tag, Entities\WikiEntity::TYPE_BOOK, $activeOnly);
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        return $this->dataAccess->getAllByUserForPage($page, $limit, $user, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Entities\WikiEntity[]
     */
    public function getAllActive()
    {
        return $this->dataAccess->getAllActive(Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Entities\WikiEntity[]
     */
    public function getAllInactive()
    {
        return $this->dataAccess->getAllInactive(Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit)
    {
        return $this->dataAccess->getAllInactiveForPage($page, $limit, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllInactiveByTagForPage($page, $limit, $tag, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  Entities\TagEntity $tag
     * @return Entities\WikiEntity[]
     */
    public function getAllActiveByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllActiveByTag($tag, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Entities\TagEntity[]
     */
    public function getAllTags()
    {
        return $this->dataAccess->getAllTags(Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param  int            $page
     * @param  int            $limit
     * @param  string         $type
     * @return Paginator|null
     */
    public function getAllWithDraftsForPage($page, $limit, $type)
    {
        $wikiIds = $this->getIdListOfWikisThatHaveDrafts();

        if (!$wikiIds) {
            return null;
        }

        $qb = $this->dao->createQueryBuilder()
            ->select('w')
            ->from(Entities\WikiEntity::class, 'w')
            ->leftJoin('w.drafts', 'd')
            ->where('w.type = :type');

        $qb->andWhere($qb->expr()->in('w.id', $wikiIds));

        $qb->setParameter('type', $type);

        $this->orderByDesc($qb, 'w');

        $this->preparePagination($qb, $page, $limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @return array
     */
    public function getIdListOfWikisThatHaveDrafts()
    {
        $qb = $this->dao->createQueryBuilder();
        $qb->select('w.id')
            ->from(Entities\WikiDraftEntity::class, 'd')
            ->leftJoin('d.wiki', 'w')
            ->distinct('w.id')
            ->where($qb->expr()->isNotNull('w.id'));

        $this->orderByDesc($qb, 'w');

        $res = [];

        foreach ($qb->getQuery()->getResult() as $i) {
            $res[] = $i['id'];
        }

        return $res;
    }

    /**
     * @param  Entities\WikiEntity      $e
     * @param  Entities\WikiDraftEntity $draft
     * @return Entities\WikiEntity
     */
    public function updateWithDraft(
        Entities\WikiEntity $e,
        Entities\WikiDraftEntity $draft
    ) {
        $e->perex         = $draft->perex;
        $e->text          = $this->htmlPurifier->purify($draft->text);
        $e->lastUpdatedBy = $draft->user;
        $e->updatedAt     = $draft->createdAt;

        foreach (array_reverse($e->drafts->toArray()) as $d) {
            if ($draft->id < $d->id) {
                break;
            }

            if ($e->contributors->contains($d->user) === false && $d->user->id !== $e->createdBy->id) {
                $e->contributors->add($d->user);
            }

            $e->drafts->removeElement($d);

            $this->em->remove($d);
        }

        $this->persistAndFlush($this->em, $e);

        return $e;
    }
}
