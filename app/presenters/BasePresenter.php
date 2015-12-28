<?php

namespace App\Presenters;

use App\Model\Crud;
use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Crud\ArticleCrud @inject */
    public $articleCrud;

    /** @var Crud\TagCrud @inject */
    public $tagCrud;

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->articleCrud = $this->articleCrud;
        $this->template->tagCrud     = $this->tagCrud;

        $this->template->uploadDir = $this->context->parameters['uploadDir'];
    }

    protected function throw404()
    {
        $this->error();
    }
}
