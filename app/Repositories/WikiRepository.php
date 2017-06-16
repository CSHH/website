<?php

namespace App\Repositories;

use App\Dao\WikiDao;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

class WikiRepository extends BaseRepository
{
    /** @var WikiDao */
    private $dataAccess;

    /** @var ITranslator */
    private $translator;

    /** @var EntityManager */
    private $em;

    /** @var \HtmlPurifier */
    private $htmlPurifier;

    public function __construct(
        EntityDao $dao,
        WikiDao $dataAccess,
        ITranslator $translator,
        EntityManager $em,
        \HTMLPurifier $htmlPurifier
    ) {
        parent::__construct($dao);

        $this->dataAccess   = $dataAccess;
        $this->translator   = $translator;
        $this->em           = $em;
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

        if ($this->getByTagAndNameAndType($tag, $values->name, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Strings::webalize($e->name);

        if ($this->getByTagAndSlugAndType($tag, $e->slug, $type)) {
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

        if ($e->tag->id !== $tag->id && $this->getByTagAndNameAndType($tag, $values->name, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Strings::webalize($e->name);

        if ($e->tag->id !== $tag->id && $this->getByTagAndSlugAndType($tag, $e->slug, $type)) {
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
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $type, $activeOnly = false)
    {
        return $this->dataAccess->getAllForPage($page, $limit, $type, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity    $tag
     * @param  string                $type
     * @return Entities\WikiEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag, $type)
    {
        return $this->dataAccess->getAllByTag($tag, $type);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->dataAccess->getByTagAndName($tag, $name);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->dataAccess->getByTagAndSlug($tag, $slug);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $name
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndNameAndType(Entities\TagEntity $tag, $name, $type)
    {
        return $this->dataAccess->getByTagAndNameAndType($tag, $name, $type);
    }

    /**
     * @param  Entities\TagEntity       $tag
     * @param  string                   $slug
     * @param  string                   $type
     * @return Entities\WikiEntity|null
     */
    public function getByTagAndSlugAndType(Entities\TagEntity $tag, $slug, $type)
    {
        return $this->dataAccess->getByTagAndSlugAndType($tag, $slug, $type);
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
        return $this->dataAccess->getAllByTagForPage($page, $limit, $tag, $type, $activeOnly);
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
        return $this->dataAccess->getAllByUserForPage($page, $limit, $user, $type);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  string    $type
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit, $type)
    {
        return $this->dataAccess->getAllInactiveForPage($page, $limit, $type);
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
        return $this->dataAccess->getAllInactiveByTagForPage($page, $limit, $tag, $type);
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

            if ($e->contributors->contains($d->user) === false) {
                $e->contributors->add($d->user);
            }

            $e->drafts->removeElement($d);

            $this->em->remove($d);
        }

        $this->persistAndFlush($this->em, $e);

        return $e;
    }
}
