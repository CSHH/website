<?php

namespace App\Presenters;

use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected function throw404()
    {
        $this->error();
    }
}
