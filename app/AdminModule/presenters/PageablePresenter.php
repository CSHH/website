<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;
use App\Presenters\PageableTrait;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PageablePresenter extends SecurePresenter
{
    use PageableTrait;

    /** @var string @persistent */
    public $inactiveOnly = 'no';

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

    protected function checkIfDisplayInactiveOnly()
    {
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }
    }
}
