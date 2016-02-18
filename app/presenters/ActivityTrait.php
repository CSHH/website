<?php

namespace App\Presenters;

trait ActivityTrait
{
    /** @var string @persistent */
    public $inactiveOnly = 'no';

    /** @var bool */
    protected $displayInactiveOnly = false;

    protected function checkIfDisplayInactiveOnly()
    {
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }
    }
}
