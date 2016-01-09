<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;

final class BookPresenter extends SharedContentPresenter
{
    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_BOOK);
    }
}
