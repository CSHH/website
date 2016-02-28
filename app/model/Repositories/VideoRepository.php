<?php

namespace App\Model\Repositories;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Entities;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\InvalidVideoUrlException;
use App\Model\Videos;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;
use Nette\Localization\ITranslator;
use HeavenProject\Utils\Slugger;

class VideoRepository extends SingleUserContentRepository
{
    use DuplicityChecker;

    /** @var string */
    protected $vimeoOembedEndpoint;

    /** @var ITranslator */
    private $translator;

    /**
     * @param string        $vimeoOembedEndpoint
     * @param EntityDao     $dao
     * @param ITranslator   $translator
     * @param EntityManager $em
     */
    public function __construct(
        $vimeoOembedEndpoint,
        EntityDao $dao,
        ITranslator $translator,
        EntityManager $em
    ) {
        parent::__construct($dao, $em);

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
     * @param  Entities\VideoEntity $e
     */
    public function delete(Entities\VideoEntity $e)
    {
        $this->removeAndFlush($this->em, $e);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @param  bool      $activeOnly
     * @return Paginator
     */
    public function getAllForPage($page, $limit, $activeOnly = false)
    {
        return $this->doGetAllForPage(Entities\VideoEntity::getClassName(), $page, $limit, $activeOnly);
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\VideoEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->doGetAllByTag(Entities\VideoEntity::getClassName(), $tag);
    }

    /**
     * @param  Entities\TagEntity $tag
     * @param  string $name
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        return $this->doGetByTagAndName(Entities\VideoEntity::getClassName(), $tag, $name);
    }

    /**
     * @param  Entities\TagEntity $tag
     * @param  string $slug
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        return $this->doGetByTagAndSlug(Entities\VideoEntity::getClassName(), $tag, $slug);
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
        return $this->doGetAllByTagForPage(Entities\VideoEntity::getClassName(), $page, $limit, $tag, $activeOnly);
    }

    /**
     * @param  int                 $page
     * @param  int                 $limit
     * @param  Entities\UserEntity $user
     * @return Paginator
     */
    public function getAllByUserForPage($page, $limit, Entities\UserEntity $user)
    {
        return $this->doGetAllByUserForPage(Entities\VideoEntity::getClassName(), $page, $limit, $user);
    }

    /**
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllInactiveForPage($page, $limit)
    {
        return $this->doGetAllInactiveForPage(Entities\VideoEntity::getClassName(), $page, $limit);
    }

    /**
     * @param  int                $page
     * @param  int                $limit
     * @param  Entities\TagEntity $tag
     * @return Paginator
     */
    public function getAllInactiveByTagForPage($page, $limit, Entities\TagEntity $tag)
    {
        return $this->doGetAllInactiveByTagForPage(Entities\VideoEntity::getClassName(), $page, $limit, $tag);
    }

    /**
     * @param  Entities\VideoEntity     $e
     * @param  string                   $url
     * @throws InvalidVideoUrlException
     */
    private function processVideo(Entities\VideoEntity $e, $url)
    {
        if (Strings::contains($url, Entities\VideoEntity::TYPE_YOUTUBE . '.com')) {
            $e->type = Entities\VideoEntity::TYPE_YOUTUBE;
            $e->url  = $url ?: null;

            if ($url) {
                $video  = new Videos\Youtube($this->translator);
                $e->src = $video->getVideoSrc($url);
            } else {
                $e->src = null;
            }

        } elseif (Strings::contains($url, Entities\VideoEntity::TYPE_VIMEO . '.com')) {
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
}
