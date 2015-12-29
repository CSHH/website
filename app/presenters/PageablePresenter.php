<?php

namespace App\Presenters;

use App\Components\Controls;

abstract class PageablePresenter extends BasePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Controls\VisualPaginator */
    protected $vp;

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
}
