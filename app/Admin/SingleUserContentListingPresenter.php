<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

abstract class SingleUserContentListingPresenter extends PageablePresenter
{
    /**
     * @param Repositories\BaseRepository $repository
     * @param int                         $limit
     * @param Entities\UserEntity         $user
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $limit, Entities\UserEntity $user)
    {
        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->filter->displayInactive()) {
            $this->items = $repository->getAllInactiveForPage($this->vp->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->vp->page, $limit, $user);
        }

        $this->preparePaginator($this->items->count(), $limit);
    }
}
