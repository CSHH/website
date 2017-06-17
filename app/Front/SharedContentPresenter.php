<?php

namespace App\Front;

use App\Presenters\ActivityTrait;
use App\Entities;
use App\Repositories;

abstract class SharedContentPresenter extends PageablePresenter
{
    use ActivityTrait;

    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

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
     * @param string $tagSlug
     * @param string $slug
     * @param string $type
     */
    protected function runActionDetail($tagSlug, $slug, $type)
    {
        $tag = $this->getTag($tagSlug);

        $this->throw404IfNoTagOrSlug($tag, $slug);

        $wiki = $this->wikiRepository->getByTagAndSlug($tag, $slug);

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
     * @param string $tagSlug
     * @param int    $limit
     * @param string $type
     */
    protected function runActionDefault($tagSlug, $limit, $type)
    {
        $tag = $this->getTag($tagSlug);

        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->filter->displayInactive()) {
            $wikis = $tag
                ? $this->wikiRepository->getAllInactiveByTagForPage($this->vp->page, $limit, $tag, $type)
                : $this->wikiRepository->getAllInactiveForPage($this->vp->page, $limit, $type);
        } else {
            $state = !$this->canAccess;

            $wikis = $tag
                ? $this->wikiRepository->getAllByTagForPage($this->vp->page, $limit, $tag, $type, $state)
                : $this->wikiRepository->getAllForPage($this->vp->page, $limit, $type, $state);
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
     * @param int $wikiId
     */
    public function handleActivate($wikiId)
    {
        $wiki = $this->getItem($wikiId, $this->wikiRepository);

        if (!$wiki) {
            $this->throw404();
        }

        $this->wikiRepository->activate($wiki);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $wiki = $this->getItem($wikiId, $this->wikiRepository);

        if (!$wiki) {
            $this->throw404();
        }

        $this->wikiRepository->delete($wiki);

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
