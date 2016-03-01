<?php

namespace App\Presenters;

use App\Components\Controls;

trait PageableTrait
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Controls\VisualPaginatorControl */
    protected $vp;

    /**
     * @return Controls\VisualPaginatorControl
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
        $this->vp = new Controls\VisualPaginatorControl($this->page);
        $p        = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->page);
    }
}
