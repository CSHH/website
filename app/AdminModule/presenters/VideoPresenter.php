<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;

final class VideoPresenter extends SingleUserContentPresenter
{
    /** @var Entities\BaseEntity */
    private $item;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        if ($id !== null) {
            $item = $this->videoRepository->getById($id);
            $user = $this->getLoggedUserEntity();
            if (!$item || $item->user->id !== $user->id) {
                $this->flashMessage($this->translator->translate('locale.item.does_not_exist'));
                $this->redirect('Video:default');
            }

            $this->item = $item;
        }
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->videoRepository, 10, $this->getLoggedUserEntity());
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
