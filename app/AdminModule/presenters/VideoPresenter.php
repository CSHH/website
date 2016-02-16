<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;

final class VideoPresenter extends SingleUserContentPresenter
{
    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->videoRepository, 'Video:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->videoRepository, 10, $this->getLoggedUserEntity());
    }

    /**
     * @param int $videoId
     */
    public function handleActivate($videoId)
    {
        $this->runHandleActivate($videoId, $this->videoRepository);
    }

    /**
     * @param int $videoId
     */
    public function handleDelete($videoId)
    {
        $this->runHandleDelete($videoId, $this->videoRepository);
    }

    /**
     * @return Forms\VideoForm
     */
    protected function createComponentForm()
    {
        return new Forms\VideoForm(
            $this->translator,
            $this->tagRepository,
            $this->videoRepository,
            $this->getLoggedUserEntity(),
            $this->item
        );
    }
}
