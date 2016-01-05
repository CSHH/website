<?php

namespace App\FrontModule\Presenters;

use App;
use App\Model\Crud;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Crud\ArticleCrud @inject */
    public $articleCrud;

    /** @var Crud\ImageCrud @inject */
    public $imageCrud;

    /** @var Crud\VideoCrud @inject */
    public $videoCrud;

    /** @var Crud\WikiCrud @inject */
    public $wikiCrud;

    /** @var Crud\TagCrud @inject */
    public $tagCrud;

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->articleCrud = $this->articleCrud;
        $this->template->imageCrud   = $this->imageCrud;
        $this->template->videoCrud   = $this->videoCrud;
        $this->template->wikiCrud    = $this->wikiCrud;
        $this->template->tagCrud     = $this->tagCrud;

        $this->template->uploadDir = $this->context->parameters['uploadDir'];
    }
}
