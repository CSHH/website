<?php

namespace App\Admin;

use App\Entities;
use App\Forms;

final class BookDetailPresenter extends SharedContentDetailPresenter
{
    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm(Entities\WikiEntity::TYPE_BOOK, 'BookListing:default', $id);
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->wikiRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'BookListing:default');

        $this->item = $item;
    }

    public function renderDetail()
    {
        $this->template->item = $this->item;
    }

    /**
     * @param int $id
     */
    public function actionActivate($id)
    {
        $this->runHandleActivate($id, $this->wikiRepository, 'BookListing:default');
    }

    /**
     * @param int $id
     */
    public function actionDelete($id)
    {
        $this->runHandleDelete($id, $this->wikiRepository, 'BookListing:default');
    }

    /**
     * @return Forms\WikiForm
     */
    protected function createComponentWikiForm()
    {
        return $this->runCreateComponentWikiForm(Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Forms\WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return $this->runCreateComponentWikiDraftForm(Entities\WikiEntity::TYPE_BOOK);
    }
}
