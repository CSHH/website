<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;

final class VideoPresenter extends SingleUserContentPresenter
{
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
        $video = $videoId ? $this->videoRepository->getById($videoId) : null;

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
        $video = $videoId ? $this->videoRepository->getById($videoId) : null;

        if (!$video) {
            $this->throw404();
        }

        $this->videoRepository->delete($video);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'));
    }
}
