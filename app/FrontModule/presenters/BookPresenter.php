<?php

namespace App\FrontModule\Presenters;

use App\Components\Forms;
use App\Entities;

final class BookPresenter extends SharedContentPresenter
{
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
}
