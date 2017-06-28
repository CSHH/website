<?php

namespace App\Admin;

use App\Repositories;

final class WikiDraftBookPresenter extends WikiDraftPresenter
{
    /** @var Repositories\BookRepository @inject */
    public $bookRepository;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->callActionDefault($this->bookRepository, $wikiId);
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
        $this->callHandleActivate($this->bookRepository, $wikiId, $id);
    }
}
