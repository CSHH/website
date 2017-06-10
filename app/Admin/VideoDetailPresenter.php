<?php

namespace App\Admin;

use App\Forms;

final class VideoDetailPresenter extends SingleUserContentDetailPresenter
{
    /** @var Forms\VideoFormInterface @inject */
    public $videoForm;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->videoRepository, 'VideoListing:default', $id);
    }

    public function renderForm()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->videoRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'VideoListing:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $id
     */
    public function actionActivate($id)
    {
        $this->runHandleActivate($id, $this->videoRepository, 'VideoListing:default');
    }

    /**
     * @param int $id
     */
    public function actionDelete($id)
    {
        $this->runHandleDelete($id, $this->videoRepository, 'VideoListing:default');
    }

    /**
     * @return Forms\VideoForm
     */
    protected function createComponentForm()
    {
        return $this->videoForm->create(
            $this->loggedUser->getLoggedUserEntity(),
            $this->item
        );
    }
}
