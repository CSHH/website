<?php

namespace App\Admin;

use App\Repositories;

final class WikiDraftMoviePresenter extends WikiDraftPresenter
{
    /** @var Repositories\MovieRepository @inject */
    public $movieRepository;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->callActionDefault($this->movieRepository, $wikiId, 'Movie:default');
    }

    /**
     * @param int $wikiId
     * @param int $id
     */
    public function handleActivate($wikiId, $id)
    {
        $this->callHandleActivate($this->movieRepository, $wikiId, $id, 'Movie:default');
    }
}
