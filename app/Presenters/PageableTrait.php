<?php

namespace App\Presenters;

use App\Components;

trait PageableTrait
{
    /** @var Components\VisualPaginatorControlInterface @inject */
    public $visualPaginatorControl;

    /** @var Components\VisualPaginatorControl */
    protected $vp;

    private function registerPaginator()
    {
        $paginator  = $this->visualPaginatorControl->create();
        $this->vp   = $paginator;
        $this['vp'] = $paginator;
    }

    /**
     * @param int $itemCount
     * @param int $limit
     */
    protected function preparePaginator($itemCount, $limit)
    {
        $p = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->vp->page);
    }
}
