<?php

namespace App\Admin;

use App\Entities;
use App\Presenters\ActivityTrait;
use App\Presenters\PageableTrait;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends SecurePresenter
{
    use ActivityTrait;
    use PageableTrait;

    /** @var Paginator */
    protected $items;

    /** @var Entities\BaseEntity */
    protected $item;

    /** @var bool */
    protected $canAccess = false;

    protected function startup()
    {
        parent::startup();

        $this->registerFilter();
        $this->registerPaginator();
    }

    public function renderDefault()
    {
        $this->template->canAccess = $this->canAccess;
        $this->template->items     = $this->items;
    }
}
