<?php

namespace App\Presenters;

use App\Model\Crud;
use Nette;
use Nette\Localization\ITranslator;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var ITranslator @inject */
    public $translator;

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

    protected function throw404()
    {
        $this->error();
    }
}
