<?php

namespace App\Admin;

use App\Repositories;

final class WikiDraftGamePresenter extends WikiDraftPresenter
{
    /** @var Repositories\GameRepository @inject */
    public $gameRepository;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->callActionDefault($this->gameRepository, $wikiId, 'Game:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleActivate($wikiId, $id)
    {
        $this->callHandleActivate($this->gameRepository, $wikiId, $id, 'Game:default');
    }
}
