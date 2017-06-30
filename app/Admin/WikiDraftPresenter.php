<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

abstract class WikiDraftPresenter extends SharedContentPresenter
{
    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /** @var Entities\WikiEntity */
    protected $wiki;

    /** @var Entities\WikiDraftEntity */
    protected $wikiDraft;

    /**
     * @param Repositories\WikiRepository $repository
     * @param int                         $wikiId
     * @param string                      $redirect
     */
    protected function callActionDefault(Repositories\WikiRepository $repository, $wikiId, $redirect)
    {
        $this->wiki = $this->getItem($wikiId, $repository);

        if (!$this->wiki) {
            $this->redirect($redirect);
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
     * @param Repositories\WikiRepository $repository
     * @param int                         $wikiId
     * @param int                         $id
     * @param string                      $redirect
     */
    protected function callHandleActivate(Repositories\WikiRepository $repository, $wikiId, $id, $redirect)
    {
        $wikiDraft = $this->getItem($id, $this->wikiDraftRepository);
        $this->checkWikiDraft($wikiDraft, $wikiId);
        $repository->updateWithDraft($wikiDraft->wiki, $wikiDraft);
        $this->flashMessage($this->translator->translate('locale.item.activated'));
        $this->redirect($redirect);
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
        $this->flashMessage($this->translator->translate('locale.item.deleted'));
        $this->redirect('default', ['wikiId' => $wikiId]);
    }

    /**
     * @param Entities\WikiDraftEntity $wikiDraft
     * @param int                      $wikiId
     */
    protected function checkWikiDraft(Entities\WikiDraftEntity $wikiDraft, $wikiId)
    {
        if (!$wikiDraft || $wikiDraft->wiki->id != $wikiId) {
            $this->redirect('Homepage:default');
        }
    }
}
