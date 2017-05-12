<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;

final class GamePresenter extends SharedContentPresenter
{
    /** @var Components\TagsControlInterface @inject */
    public $tagsControl;

    /** @var Caching\GameTagSectionCache @inject */
    public $gameTagSectionCache;

    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($tagSlug, $slug, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->gameTagSectionCache);
    }
}
