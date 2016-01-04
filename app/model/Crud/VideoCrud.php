<?php

namespace App\Model\Crud;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Entities;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\InvalidVideoUrlException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;
use Nette\Localization\ITranslator;
use HeavenProject\Utils\Slugger;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class VideoCrud extends BaseCrud
{
    use DuplicityChecker;

    /** @var string */
    protected $vimeoOembedEndpoint;

    /** @var ITranslator */
    private $translator;

    /** @var EntityManager */
    private $em;

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
        parent::__construct($dao);

        $this->vimeoOembedEndpoint = $vimeoOembedEndpoint;
        $this->translator          = $translator;
        $this->em                  = $em;
    }

    public function create(
        ArrayHash $values,
        Entities\TagEntity $tag,
        Entities\UserEntity $user,
        Entities\VideoEntity $e
    ) {
        $src = $values->src;
        unset($values->src);

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

        if (Strings::contains($src, Entities\VideoEntity::TYPE_YOUTUBE . '.com')) {
            $e->type = Entities\VideoEntity::TYPE_YOUTUBE;
            $e->youtubeVideoSrc = $src ?: null;
            $e->youtubeVideoUrl = $src ? $this->getYoutubeVideoUrl($src) : null;

        } elseif (Strings::contains($src, Entities\VideoEntity::TYPE_VIMEO . '.com')) {
            $e->type = Entities\VideoEntity::TYPE_VIMEO;
            $e->vimeoVideoSrc = $src ?: null;
            $e->vimeoVideoUrl = $src ? $this->getVimeoVideoUrl($src) : null;

        } else {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_video_url')
            );
        }

        $e->tag  = $tag;
        $e->user = $user;

        $this->em->persist($e);
        $this->em->flush();

        return $e;
    }

    /**
     * @param  Entities\TagEntity $tag
     * @param  string $name
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndName(Entities\TagEntity $tag, $name)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('v')
                ->from(Entities\VideoEntity::getClassName(), 'v')
                ->join('v.tag', 't')
                ->where('t.id = :tagId AND v.name = :name')
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
     * @param  Entities\TagEntity $tag
     * @param  string $slug
     * @return Entities\VideoEntity|null
     */
    public function getByTagAndSlug(Entities\TagEntity $tag, $slug)
    {
        try {
            return $this->dao->createQueryBuilder()
                ->select('v')
                ->from(Entities\VideoEntity::getClassName(), 'v')
                ->join('v.tag', 't')
                ->where('t.id = :tagId AND v.slug = :slug')
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
     * @param  int       $page
     * @param  int       $limit
     * @return Paginator
     */
    public function getAllForPage($page, $limit)
    {
        $qb = $this->dao->createQueryBuilder()
            ->select('v')
            ->from(Entities\VideoEntity::getClassName(), 'v')
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
            ->select('v')
            ->from(Entities\VideoEntity::getClassName(), 'v')
            ->join('v.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  Entities\TagEntity     $tag
     * @return Entities\VideoEntity[]
     */
    public function getAllByTag(Entities\TagEntity $tag)
    {
        return $this->dao->createQueryBuilder()
            ->select('v')
            ->from(Entities\VideoEntity::getClassName(), 'v')
            ->join('v.tag', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->id)
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
            ->select('v')
            ->from(Entities\VideoEntity::getClassName(), 'v')
            ->join('v.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->id)
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param  string $pageUrl
     * @throws InvalidVideoUrlException
     * @return string
     */
    private function getYoutubeVideoUrl($pageUrl)
    {
        $key = 'watch?v=';

        if (!Strings::contains($pageUrl, $key)) {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_youtube_video_url')
            );
        }

        return str_replace($key, 'embed/', $pageUrl);
    }

    /**
     * @param  string $pageUrl
     * @return string
     */
    private function getVimeoVideoUrl($pageUrl)
    {
        $url = $this->vimeoOembedEndpoint . '?url=' . rawurlencode($pageUrl);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($result);

        $iframe = (string) $xml->html;

        $part = substr($iframe, strpos($iframe, 'src="') + 5);

        return substr($part, 0, strpos($part, '"'));
    }
}
