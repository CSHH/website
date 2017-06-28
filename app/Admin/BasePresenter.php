<?php

namespace App\Admin;

use App;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->uploadDir = $this->context->parameters['uploadDir'];
    }
}
