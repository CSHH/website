<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;

final class BookPresenter extends SharedContentPresenter
{
    /** @var Components\TagsControlInterface @inject */
    public $tagsControl;

    /** @var Caching\BookTagSectionCache @inject */
    public $bookTagSectionCache;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($tagSlug, $slug, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->bookTagSectionCache);
    }
}
