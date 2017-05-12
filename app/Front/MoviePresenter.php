<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;

final class MoviePresenter extends SharedContentPresenter
{
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
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_MOVIE);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($tagSlug, $slug, Entities\WikiEntity::TYPE_MOVIE);
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->movieTagSectionCache);
    }
}
