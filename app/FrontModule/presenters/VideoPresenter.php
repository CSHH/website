<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;
use App\Model\Videos\VideoThumbnail;

final class VideoPresenter extends SingleUserContentPresenter
{
    /** @var Repositories\VideoRepository @inject */
    public $videoRepository;

    /** @var Entities\VideoEntity[] */
    private $videos;

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

        $parameters = $this->context->parameters;

        $this->template->videos         = $this->videos;
        $this->template->videoThumbnail = new VideoThumbnail($parameters['wwwDir'], $parameters['videoThumbnailsDir'], $parameters['vimeoOembedEndpoint']);
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
