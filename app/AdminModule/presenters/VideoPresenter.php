<?php

namespace App\AdminModule\Presenters;

use App\Components\Forms;
use App\Videos\VideoThumbnail;

final class VideoPresenter extends SingleUserContentPresenter
{
    /** @var Forms\VideoFormInterface @inject */
    public $videoForm;

    /** @var VideoThumbnail @inject */
    public $videoThumbnail;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->videoRepository, 'Video:default', $id);
    }

    public function renderForm()
    {
        $this->template->item = $this->item;
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->videoRepository, 10, $this->getLoggedUserEntity());
    }

    public function renderDefault()
    {
        parent::renderDefault();

        $this->template->videoThumbnail = $this->videoThumbnail;
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->videoRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'Video:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
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
        return $this->videoForm->create(
            $this->getLoggedUserEntity(),
            $this->item
        );
    }
}
