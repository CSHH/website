<?php

namespace App\Admin;

use App\Entities;
use App\Forms;
use App\Repositories;

final class MoviePresenter extends SharedContentPresenter
{
    /** @var Repositories\MovieRepository @inject */
    public $movieRepository;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm($this->movieRepository, Entities\WikiEntity::TYPE_MOVIE, 'Movie:default', $id);
    }

    public function actionDefault()
    {
        $this->runActionDefault($this->movieRepository, 10, Entities\WikiEntity::TYPE_MOVIE);
    }

    /**
     * @param int $id
     */
    public function actionDetail($id)
    {
        $item = $this->getItem($id, $this->movieRepository);

        $this->checkItemAndFlashWithRedirectIfNull($item, 'Movie:default');

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
        $this->runHandleActivate($this->movieRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $this->runHandleDelete($this->movieRepository, $wikiId);
    }

    /**
     * @return Forms\WikiForm
     */
    protected function createComponentWikiForm()
    {
        return $this->runCreateComponentWikiForm($this->movieRepository, Entities\WikiEntity::TYPE_MOVIE);
    }

    /**
     * @return Forms\WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return $this->runCreateComponentWikiDraftForm(Entities\WikiEntity::TYPE_MOVIE);
    }
}
