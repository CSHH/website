<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

final class WikiDraftPresenter extends SharedContentPresenter
{
    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /** @var Entities\WikiEntity */
    private $wiki;

    /** @var Entities\WikiDraftEntity */
    private $wikiDraft;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->wiki = $this->getItem($wikiId, $this->wikiRepository);

        if (!$this->wiki) {
            $this->redirect('Homepage:default');
        }
    }

    public function renderDefault()
    {
        $this->template->wiki = $this->wiki;
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
        $wikiDraft = $this->getItem($id, $this->wikiDraftRepository);

        $this->checkWikiDraft($wikiDraft, $wikiId);

        $this->wikiRepository->updateWithDraft($wikiDraft->wiki, $wikiDraft);
        $this->redirect('Homepage:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleDelete($wikiId, $id)
    {
        $wikiDraft = $this->getItem($id, $this->wikiDraftRepository);

        $this->checkWikiDraft($wikiDraft, $wikiId);

        $this->wikiDraftRepository->delete($wikiDraft);
        $this->redirect('default', array('wikiId' => $wikiId));
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
