<?php

namespace App\AdminModule\Presenters;

use App\Components\Controls;
use App\Model\Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends SecurePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var string @persistent */
    public $inactiveOnly = 'no';

    /** @var Controls\VisualPaginator */
    protected $vp;

    /** @var Paginator */
    protected $items;

    /** @var Entities\BaseEntity */
    protected $item;

    /** @var bool */
    protected $displayInactiveOnly = false;

    /** @var bool */
    protected $canAccess = false;

    public function renderDefault()
    {
        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
        $this->template->items        = $this->items;
    }

    /**
     * @return Controls\VisualPaginator
     */
    protected function createComponentVp()
    {
        return $this->vp;
    }

    /**
     * @param int $itemCount
     * @param int $limit
     */
    protected function preparePaginator($itemCount, $limit)
    {
        $this->vp = new Controls\VisualPaginator($this->page);
        $p        = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->page);
    }

    /**
     * @param string $message
     * @param string $redirect
     */
    protected function flashWithRedirect($message = '', $redirect = 'this')
    {
        $this->flashMessage($message);
        $this->redirect($redirect);
    }

    protected function checkIfDisplayInactiveOnly()
    {
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }
    }
}
