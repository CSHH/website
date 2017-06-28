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
        $this->callActionDefault($this->gameRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function actionDetail($wikiId, $id)
    {
        $this->wikiDraft = $this->getItem($id, $this->wikiDraftRepository);

        $this->checkWikiDraft($this->wikiDraft, $wikiId);
    }

    public function renderDetail()
    {
        $this->template->draft = $this->wikiDraft;
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleActivate($wikiId, $id)
    {
        $this->callHandleActivate($this->gameRepository, $wikiId, $id);
    }
}
