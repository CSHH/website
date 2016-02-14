<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms;
use App\Model\Entities;

final class GamePresenter extends SharedContentPresenter
{
    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm(Entities\WikiEntity::TYPE_GAME, 'Game:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault(10, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @return Forms\WikiForm
     */
    protected function createComponentWikiForm()
    {
        return $this->runCreateComponentWikiForm(Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @return Forms\WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return $this->runCreateComponentWikiDraftForm(Entities\WikiEntity::TYPE_GAME);
    }
}
