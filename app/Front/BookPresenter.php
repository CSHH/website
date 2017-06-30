<?php

namespace App\Front;

use App\Caching;
use App\Components;
use App\Entities;
use App\Forms;
use App\Repositories;

final class BookPresenter extends SharedContentPresenter
{
    /** @var Repositories\BookRepository @inject */
    public $bookRepository;

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
        $this->runActionDefault($this->bookRepository, $tagSlug, 10);
    }

    /**
     * @param string $tagSlug
     * @param string $slug
     */
    public function actionDetail($tagSlug, $slug)
    {
        $this->runActionDetail($this->bookRepository, $tagSlug, $slug, Entities\WikiEntity::TYPE_BOOK);
    }

    /**
     * @param int $wikiId
     */
    public function handleActivate($wikiId)
    {
        $this->runHandleActivate($this->bookRepository, $wikiId);
    }

    /**
     * @param int $wikiId
     */
    public function handleDelete($wikiId)
    {
        $this->runHandleDelete($this->bookRepository, $wikiId);
    }

    /**
     * @return Components\TagsControlInterface
     */
    protected function createComponentTagsControl()
    {
        return $this->tagsControl->create($this->bookTagSectionCache);
    }
}
