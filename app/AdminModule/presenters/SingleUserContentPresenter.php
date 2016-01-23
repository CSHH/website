<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /** @var bool */
    private $inactiveOnly = false;

    /** @var bool */
    private $canAccess = false;

    /** @var Paginator */
    private $items;

    /**
     * @param  Repositories\BaseRepository $repository
     * @param  int                         $limit
     * @param  Entities\UserEntity         $user
     */
    protected function runActionDefault(Repositories\BaseRepository $repository, $limit, Entities\UserEntity $user)
    {
        $this->inactiveOnly = $this->displayInactiveOnly();

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->inactiveOnly) {
            $this->items = $repository->getAllInactiveForPage($this->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->page, $limit, $user);
        }

        $this->preparePaginator($this->items->count(), $limit);
    }

    public function renderDefault()
    {
        $this->template->inactiveOnly = $this->inactiveOnly;
        $this->template->canAccess    = $this->canAccess;
        $this->template->items        = $this->items;
    }

    /**
     * @return bool
     */
    private function displayInactiveOnly()
    {
        return $this->getHttpRequest()->getQuery('inactiveOnly') === '' ? true : false;
    }

    /**
     * @return bool
     */
    private function canAccess()
    {
        $user = $this->getLoggedUserEntity();

        return $user && $user->role > Entities\UserEntity::ROLE_USER;
    }
}
