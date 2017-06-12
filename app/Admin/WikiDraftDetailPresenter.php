<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

final class WikiDraftDetailPresenter extends SharedContentDetailPresenter
{
    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /** @var Entities\WikiDraftEntity */
    private $wikiDraft;

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
    public function actionActivate($wikiId, $id)
    {
        $wikiDraft = $this->getItem($id, $this->wikiDraftRepository);

        $this->checkWikiDraft($wikiDraft, $wikiId);

        $this->wikiRepository->updateWithDraft($wikiDraft->wiki, $wikiDraft);
        $this->redirect('Homepage:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function actionDelete($wikiId, $id)
    {
        $wikiDraft = $this->getItem($id, $this->wikiDraftRepository);

        $this->checkWikiDraft($wikiDraft, $wikiId);

        $this->wikiDraftRepository->delete($wikiDraft);
        $this->redirect('WikiDraftListing:default', ['wikiId' => $wikiId]);
    }

    /**
     * @param Entities\WikiDraftEntity $wikiDraft
     * @param int                      $wikiId
     */
    private function checkWikiDraft(Entities\WikiDraftEntity $wikiDraft, $wikiId)
    {
        if (!$wikiDraft || $wikiDraft->wiki->id != $wikiId) {
            $this->redirect('Homepage:default');
        }
    }
}
