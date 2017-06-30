<?php

namespace App\Admin;

use App\Entities;
use App\Forms;
use App\Repositories;

final class BookPresenter extends SharedContentPresenter
{
    /** @var Repositories\BookRepository @inject */
    public $bookRepository;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->bookRepository, Entities\WikiEntity::TYPE_BOOK, 'Book:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->bookRepository, 10, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->bookRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'Book:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $wikiId
     */
    public function handleActivate($wikiId)
    {
        $this->runHandleActivate($this->bookRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $this->runHandleDelete($this->bookRepository, $wikiId);
    }

    /**
     * @return Forms\WikiForm
     */
    protected function createComponentWikiForm()
    {
        return $this->runCreateComponentWikiForm($this->bookRepository, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Forms\WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return $this->runCreateComponentWikiDraftForm(Entities\WikiEntity::TYPE_BOOK);
    }
}
