<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;
use App\Model\Videos\VideoThumbnail;

final class VideoPresenter extends SingleUserContentPresenter
{
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
        $this->videos = $this->runActionDefault($this->videoRepository, $tagSlug, 54);
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
        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

        $video = $this->videoRepository->getByTagAndSlug($tag, $slug);

        if ((!$video || !$video->isActive) && !$this->canAccess()) {
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
}
