<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;
use App\Repositories;

final class GamePresenter extends SharedContentPresenter
{
    /** @var Repositories\GameRepository @inject */
    public $gameRepository;

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
        $this->runActionDefault($this->gameRepository, $tagSlug, 10);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($this->gameRepository, $tagSlug, $slug, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @param int $wikiId
     */
    public function handleActivate($wikiId)
    {
        $this->runHandleActivate($this->gameRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $this->runHandleDelete($this->gameRepository, $wikiId);
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->gameTagSectionCache);
    }
}
