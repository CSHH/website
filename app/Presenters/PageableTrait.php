<?php

namespace App\Presenters;

use App\Components;

trait PageableTrait
{
    /** @var Components\VisualPaginatorControlInterface @inject */
    public $visualPaginatorControl;

    /** @var Components\VisualPaginatorControl */
    protected $vp;

    protected function startup()
    {
        parent::startup();

        $this->vp = $this['vp'] = $this->visualPaginatorControl->create();
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
