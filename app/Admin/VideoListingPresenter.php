<?php

namespace App\Admin;

use App\Videos\VideoThumbnail;

final class VideoListingPresenter extends SingleUserContentListingPresenter
{
    /** @var VideoThumbnail @inject */
    public $videoThumbnail;

    public function actionDefault()
    {
        $this->runActionDefault($this->videoRepository, 50, $this->loggedUser->getLoggedUserEntity());
    }

    public function renderDefault()
    {
        parent::renderDefault();

        $this->template->videoThumbnail = $this->videoThumbnail;
    }
}
