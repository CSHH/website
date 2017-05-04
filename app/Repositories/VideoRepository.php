<?php

namespace App\Repositories;

use App\Caching;
use App\Dao\SingleUserContentDao;
use App\Duplicities\DuplicityChecker;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Exceptions\InvalidVideoUrlException;
use App\Utils\PaginatorFactory;
use App\Videos;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HeavenProject\Utils\Slugger;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

class VideoRepository extends SingleUserContentRepository
{
    use DuplicityChecker;

    /** @var string */
    protected $vimeoOembedEndpoint;

    /** @var ITranslator */
    private $translator;

    /**
     * @param string                       $vimeoOembedEndpoint
     * @param EntityDao                    $dao
     * @param SingleUserContentDao         $dataAccess
     * @param ITranslator                  $translator
     * @param EntityManager                $em
     * @param Caching\VideoTagSectionCache $tagCache
     */
    public function __construct(
        $vimeoOembedEndpoint,
        EntityDao $dao,
        SingleUserContentDao $dataAccess,
        ITranslator $translator,
        EntityManager $em,
        Caching\VideoTagSectionCache $tagCache
    ) {
        parent::__construct($dao, $dataAccess, $em, $tagCache);

        $this->vimeoOembedEndpoint = $vimeoOembedEndpoint;
        $this->translator          = $translator;
    }

    public function create(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\VideoEntity $e
    ) {
        $e->setValues($values);

        if ($this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.video_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.video_tag_and_slug')
            );
        }

        $url = $e->url;

        $this->processVideo($e, $url);

        $e->tag  = $tag;
        $e->user = $user;

        $this->persistAndFlush($this->em, $e);

        return $e;
    }

    public function update(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\VideoEntity $e
    ) {
        if ($e->tag->id !== $tag->id) {
            $this->tagCache->deleteSection();
        }

        $e->setValues($values);

        if ($e->tag->id !== $tag->id && $this->getByTagAndName($tag, $values->name)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.video_tag_and_name')
            );
        }

        $e->slug = $e->slug ?: Slugger::slugify($e->name);

        if ($e->tag->id !== $tag->id && $this->getByTagAndSlug($tag, $e->slug)) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.video_tag_and_slug')
            );
        }

        $url = $e->url;

        $this->processVideo($e, $url);

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
        return $this->doActivate($e);
    }

    /**
     * @param Entities\VideoEntity $e
     */
    public function delete(Entities\VideoEntity $e)
    {
        $this->removeAndFlush($this->em, $e);

        $this->tagCache->deleteSection();
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
        return $this->dataAccess->getAllForPage(Entities\VideoEntity::class, $paginatorFactory, $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\VideoEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dataAccess->getAllByTag(Entities\VideoEntity::class, $tag);
    }

    /**
     * @param  Entities\TagEntity        $tag
     * @param  string                    $name
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->dataAccess->getByTagAndName(Entities\VideoEntity::class, $tag, $name);
    }

    /**
     * @param  Entities\TagEntity        $tag
     * @param  string                    $slug
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->dataAccess->getByTagAndSlug(Entities\VideoEntity::class, $tag, $slug);
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
        return $this->dataAccess->getAllByTagForPage(Entities\VideoEntity::class, $paginatorFactory, $page, $limit, $tag, $activeOnly);
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
        return $this->dataAccess->getAllByUserForPage(Entities\VideoEntity::class, $paginatorFactory, $page, $limit, $user);
    }

    /**
     * @param  PaginatorFactory $paginatorFactory
     * @param  int              $page
     * @param  int              $limit
     * @return Paginator
     */
    public function getAllInactiveForPage(PaginatorFactory $paginatorFactory, $page, $limit)
    {
        return $this->dataAccess->getAllInactiveForPage(Entities\VideoEntity::class, $paginatorFactory, $page, $limit);
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
        return $this->dataAccess->getAllInactiveByTagForPage(Entities\VideoEntity::class, $paginatorFactory, $page, $limit, $tag);
    }

    /**
     * @param  Entities\VideoEntity     $e
     * @param  string                   $url
     * @throws InvalidVideoUrlException
     */
    private function processVideo(Entities\VideoEntity $e, $url)
    {
        if (Strings::contains($url, Entities\VideoEntity::DOMAIN_YOUTUBE . '.com')) {
            $e->type = Entities\VideoEntity::TYPE_YOUTUBE;
            $e->url  = $url ?: null;

            if ($url) {
                $video  = new Videos\Youtube($this->translator);
                $e->src = $video->getVideoSrc($url);
            } else {
                $e->src = null;
            }
        } elseif (Strings::contains($url, Entities\VideoEntity::DOMAIN_VIMEO . '.com')) {
            $e->type = Entities\VideoEntity::TYPE_VIMEO;
            $e->url  = $url ?: null;

            if ($url) {
                $video  = new Videos\Vimeo($this->vimeoOembedEndpoint);
                $e->src = $video->getVideoSrc($url);
            } else {
                $e->src = null;
            }
        } else {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_video_url')
            );
        }
    }

    /**
     * @return Entities\VideoEntity[]
     */
    public function getLatestVideos()
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('e')
            ->from(Entities\VideoEntity::class, 'e')
            ->where('e.isActive = :state')
            ->orderBy('e.updatedAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(12)
            ->setParameter('state', true);

        return $qb->getQuery()
            ->getResult();
    }
}
