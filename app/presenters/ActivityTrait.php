<?php

namespace App\Presenters;

use App\Utils\Activity;

trait ActivityTrait
{
    /** @var string @persistent */
    public $inactiveOnly = Activity::DISPLAY_DEFAULT;

    /** @var bool */
    protected $displayInactiveOnly = false;

    protected function checkIfDisplayInactiveOnly()
    {
        if ($this->inactiveOnly === Activity::DISPLAY_FILTERED) {
            $this->displayInactiveOnly = true;
        }
    }
}
