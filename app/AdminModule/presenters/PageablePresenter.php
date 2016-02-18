<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
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

    public function renderDefault()
    {
        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
        $this->template->items        = $this->items;
    }
}
