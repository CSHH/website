<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Repositories;
use App\Videos\VideoThumbnail;

final class VideoPresenter extends SingleUserContentPresenter
{
    /** @var Components\TagsControlInterface @inject */
    public $tagsControl;

    /** @var Caching\VideoTagSectionCache @inject */
    public $videoTagSectionCache;

    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    /** @var VideoThumbnail @inject */
    public $videoThumbnail;

    /** @var Entities\VideoEntity[] */
    private $videos;

    /** @var Entities\VideoEntity */
    private $video;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->videos = $this->runActionDefault($this->videoRepository, $tagSlug, 50);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->videos         = $this->videos;
        $this->template->videoThumbnail = $this->videoThumbnail;
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->checkBacklinks();

        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

        $video = $this->videoRepository->getByTagAndSlug($tag, $slug);

        if ((!$video || !$video->isActive) && !$this->accessChecker->canAccess()) {
            $this->throw404();
        }

        $this->video = $video;
    }

    public function renderDetail()
    {
        $this->template->video = $this->video;
    }

    /**
     * @param int $videoId
     */
    public function handleActivate($videoId)
    {
        $video = $this->getItem($videoId, $this->videoRepository);

        if (!$video) {
            $this->throw404();
        }

        $this->videoRepository->activate($video);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param int $videoId
     */
    public function handleDelete($videoId)
    {
        $video = $this->getItem($videoId, $this->videoRepository);

        if (!$video) {
            $this->throw404();
        }

        $this->videoRepository->delete($video);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'));
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->videoTagSectionCache);
    }
}
