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
}
