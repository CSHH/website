<?php

namespace App\FrontModule\Presenters;

use App;
use App\Components\Controls;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Controls\MenuControlInterface @inject */
    public $menuControl;

    /** @var bool */
    protected $canAccess = false;

    protected function startup()
    {
        parent::startup();

        $this->registerFormExtendingMethods();
    }

    /**
     * @return Controls\MenuControlInterface
     */
    protected function createComponentMenuControl()
    {
        return $this->menuControl->create();
    }
}
