<?php

namespace App\AdminModule\Presenters;

use App;
use App\Model\Crud;

abstract class BasePresenter extends App\Presenters\BasePresenter
{
    /** @var Repositories\ArticleCrud @inject */
    public $articleCrud;

    /** @var Repositories\ImageCrud @inject */
    public $imageCrud;

    /** @var Repositories\VideoCrud @inject */
    public $videoCrud;

    /** @var Repositories\WikiCrud @inject */
    public $wikiCrud;

    /** @var Repositories\TagCrud @inject */
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
