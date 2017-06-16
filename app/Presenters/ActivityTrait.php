<?php

namespace App\Presenters;

use App\Components;

trait ActivityTrait
{
    /** @var Components\FilterControlInterface @inject */
    public $filterControl;

    /** @var Components\FilterControl */
    protected $filter;

    private function registerFilter()
    {
        $filter         = $this->filterControl->create();
        $this->filter   = $filter;
        $this['filter'] = $filter;
    }
}
