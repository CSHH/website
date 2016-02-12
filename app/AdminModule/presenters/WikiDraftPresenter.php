<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;

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
        $this->wiki = $wikiId ? $this->wikiRepository->getById($wikiId) : null;

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
        $this->wikiDraft = $id ? $this->wikiDraftRepository->getById($id) : null;

        if (!$this->wikiDraft || $this->wikiDraft->wiki->id != $wikiId) {
            $this->redirect('Homepage:default');
        }
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
        $this->wikiDraft = $id ? $this->wikiDraftRepository->getById($id) : null;

        if (!$this->wikiDraft || $this->wikiDraft->wiki->id != $wikiId) {
            $this->redirect('Homepage:default');
        }

        $this->wikiRepository->updateWithDraft(
            $this->wikiDraft->wiki,
            $this->wikiDraft
        );

        $this->redirect('Homepage:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleDelete($wikiId, $id)
    {
        $this->wikiDraft = $id ? $this->wikiDraftRepository->getById($id) : null;

        if (!$this->wikiDraft || $this->wikiDraft->wiki->id != $wikiId) {
            $this->redirect('Homepage:default');
        }
    }
}
