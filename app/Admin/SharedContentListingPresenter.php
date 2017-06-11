<?php

namespace App\Admin;

abstract class SharedContentListingPresenter extends PageablePresenter
{
    /**
     * @param int    $limit
     * @param string $type
     */
    protected function runActionDefault($limit, $type)
    {
        $this->checkIfDisplayInactiveOnly();

        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $this->wikiRepository->getAllWithDraftsForPage($this->vp->page, $limit, $type);
        } else {
            $this->items = $this->wikiRepository->getAllByUserForPage($this->vp->page, $limit, $this->loggedUser->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->items ? $this->items->count() : 0, $limit);
    }
}
