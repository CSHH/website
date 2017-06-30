<?php

namespace App\Admin;

use App\Repositories;

final class WikiDraftBookPresenter extends WikiDraftPresenter
{
    /** @var Repositories\BookRepository @inject */
    public $bookRepository;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->callActionDefault($this->bookRepository, $wikiId, 'Book:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleActivate($wikiId, $id)
    {
        $this->callHandleActivate($this->bookRepository, $wikiId, $id, 'Book:default');
    }
}
