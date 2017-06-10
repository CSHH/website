<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;
use App\Utils\PaginatorFactory;

abstract class SingleUserContentListingPresenter extends PageablePresenter
{
    /** @var PaginatorFactory @inject */
    public $paginatorFactory;

    /**
     * @param Repositories\BaseRepository $repository
     * @param int                         $limit
     * @param Entities\UserEntity         $user
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $limit, Entities\UserEntity $user)
    {
        $this->checkIfDisplayInactiveOnly();

        $this->canAccess = $this->accessChecker->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $repository->getAllInactiveForPage($this->vp->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->vp->page, $limit, $user);
        }

        $this->preparePaginator($this->items->count(), $limit);
    }
}
