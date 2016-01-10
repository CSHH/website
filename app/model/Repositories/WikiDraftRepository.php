<?php

namespace App\Model\Repositories;

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
use Nette\Utils\DateTime;

class WikiDraftRepository extends BaseRepository
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
     * @param  Entities\UserEntity $user
     * @param  Entities\WikiEntity $wiki
     * @param  Entities\WikiDraftEntity $e
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\WikiEntity
     */
    public function create(
        ArrayHash $values,
        Entities\UserEntity $user,
        Entities\WikiEntity $wiki,
        Entities\WikiDraftEntity $e
    ) {
        $e->setValues($values);

        /*if ($this->getByTagAndNameAndType($tag, $values->name, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($this->getByTagAndSlugAndType($tag, $e->slug, $type)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.article_tag_and_slug')
            );
        }*/

        $e->wiki      = $wiki;
        $e->user      = $user;
        $e->createdAt = new DateTime;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    /**
     * @param  Entities\WikiEntity $wiki
     * @return Entities\WikiDraftEntity|null
     */
    public function getLatestByWiki(Entities\WikiEntity $wiki)
    {
        $res = $this->dao->createQueryBuilder()
            ->select('d')
            ->from(Entities\WikiDraftEntity::getClassName(), 'd')
            ->join('d.wiki', 'w')
            ->where('w.id = :wikiId')
            ->orderBy('d.createdAt', 'DESC')
            ->setParameter('wikiId', $wiki->id)
            ->getQuery()
            ->getResult();

        return $res ? array_shift($res) : null;
    }
}
