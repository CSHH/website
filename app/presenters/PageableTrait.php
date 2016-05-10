<?php

namespace App\Presenters;

use App\Components\Controls;

trait PageableTrait
{
    /** @var Controls\VisualPaginatorControlInterface @inject */
    public $visualPaginatorControl;

    /** @var Controls\VisualPaginatorControl */
    protected $vp;

    protected function startup()
    {
        parent::startup();

        $this->vp = $this->visualPaginatorControl->create();
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

    /**
     * @return Controls\VisualPaginatorControl
     */
    protected function createComponentVp()
    {
        return $this->vp;
    }
}
