<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class SingleUserContentPresenter extends PageablePresenter
{
    /** @var string @persistent */
    public $inactiveOnly = 'no';

    /** @var bool */
    private $displayInactiveOnly = false;

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
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->items = $repository->getAllInactiveForPage($this->page, $limit);
        } else {
            $this->items = $repository->getAllByUserForPage($this->page, $limit, $user);
        }

        $this->preparePaginator($this->items->count(), $limit);
    }

    public function renderDefault()
    {
        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
        $this->template->items        = $this->items;
    }
}
