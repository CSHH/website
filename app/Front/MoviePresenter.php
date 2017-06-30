<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;
use App\Repositories;

final class MoviePresenter extends SharedContentPresenter
{
    /** @var Repositories\MovieRepository @inject */
    public $movieRepository;

    /** @var Components\TagsControlInterface @inject */
    public $tagsControl;

    /** @var Caching\MovieTagSectionCache @inject */
    public $movieTagSectionCache;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($this->movieRepository, $tagSlug, 10);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($this->movieRepository, $tagSlug, $slug, Entities\WikiEntity::TYPE_MOVIE);
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
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->movieTagSectionCache);
    }
}
