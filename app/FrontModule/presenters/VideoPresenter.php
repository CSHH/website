<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;

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
        $this->videos = $this->runActionDefault($this->videoRepository, $tagSlug, 10);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->videos = $this->videos;
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
