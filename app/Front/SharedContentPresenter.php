<?php

namespace App\Front;

use App\Entities;
use App\Presenters\ActivityTrait;
use App\Repositories;

abstract class SharedContentPresenter extends PageablePresenter
{
    use ActivityTrait;

    /** @var Repositories\WikiDraftRepository @inject */
    public $wikiDraftRepository;

    /** @var Entities\WikiEntity[] */
    protected $wikis;

    /** @var Entities\WikiEntity */
    protected $wiki;

    protected function startup()
    {
        parent::startup();

        $this->registerFilter();
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param string                      $tagSlug
     * @param string                      $slug
     * @param string                      $type
     */
    protected function runActionDetail(Repositories\WikiRepository $repository, $tagSlug, $slug, $type)
    {
        $this->checkBacklinks();

        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

        $wiki = $repository->getByTagAndSlug($tag, $slug);

        if ((!$wiki || !$wiki->isActive) && !$this->accessChecker->canAccess()) {
            $this->throw404();
        }

        $this->wiki = $wiki;

        if ($this->loggedUser->getLoggedUserEntity()) {
            $this->createForm($type);
        }
    }

    public function renderDetail()
    {
        $this->template->wiki = $this->wiki;
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param string                      $tagSlug
     * @param int                         $limit
     */
    protected function runActionDefault(Repositories\WikiRepository $repository, $tagSlug, $limit)
    {
        $tag = $this->getTag($tagSlug);

        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->filter->displayInactive()) {
            $wikis = $tag
                ? $repository->getAllInactiveByTagForPage($this->vp->page, $limit, $tag)
                : $repository->getAllInactiveForPage($this->vp->page, $limit);
        } else {
            $state = !$this->canAccess;

            $wikis = $tag
                ? $repository->getAllByTagForPage($this->vp->page, $limit, $tag, $state)
                : $repository->getAllForPage($this->vp->page, $limit, $state);
        }

        $this->preparePaginator($wikis->count(), $limit);

        $this->throw404IfNoItemsOnPage($wikis, $tag);

        $this->wikis = $wikis;
        $this->tag   = $tag;
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->wikis = $this->wikis;
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param int                         $wikiId
     */
    protected function runHandleActivate(Repositories\WikiRepository $repository, $wikiId)
    {
        $wiki = $this->getItem($wikiId, $repository);

        if (!$wiki) {
            $this->throw404();
        }

        $repository->activate($wiki);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param Repositories\WikiRepository $repository
     * @param int                         $wikiId
     */
    protected function runHandleDelete(Repositories\WikiRepository $repository, $wikiId)
    {
        $wiki = $this->getItem($wikiId, $repository);

        if (!$wiki) {
            $this->throw404();
        }

        $repository->delete($wiki);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'));
    }

    /**
     * @param string $type
     */
    private function createForm($type)
    {
        $this['form'] = $this->wikiDraftForm->create(
            $this->loggedUser->getLoggedUserEntity(),
            $type,
            $this->wiki
        );
    }
}
