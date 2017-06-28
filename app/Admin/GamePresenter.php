<?php

namespace App\Admin;

use App\Entities;
use App\Forms;
use App\Repositories;

final class GamePresenter extends SharedContentPresenter
{
    /** @var Repositories\GameRepository @inject */
    public $gameRepository;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->gameRepository, Entities\WikiEntity::TYPE_GAME, 'Game:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->gameRepository, 10, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->gameRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'Game:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $wikiId
     */
    public function handleActivate($wikiId)
    {
        $this->runHandleActivate($this->gameRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $this->runHandleDelete($this->gameRepository, $wikiId);
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
