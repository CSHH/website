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
