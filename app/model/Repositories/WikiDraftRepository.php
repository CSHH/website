<?php

namespace App\Model\Repositories;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Utils\InputTextPurifier;
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

    /** @var InputTextPurifier */
    private $inputTextPurifier;

    public function __construct(
        EntityDao $dao,
        ITranslator $translator,
        EntityManager $em
    ) {
        parent::__construct($dao);

        $this->translator        = $translator;
        $this->em                = $em;
        $this->inputTextPurifier = new InputTextPurifier;
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

        $e->text      = $this->inputTextPurifier->purify($values->text);
        $e->wiki      = $wiki;
        $e->user      = $user;
        $e->createdAt = new DateTime;

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    /**
     * @param Entities\WikiDraftEntity $e
     */
    public function delete(Entities\WikiDraftEntity $e)
    {
        $this->removeAndFlush($this->em, $e);
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
