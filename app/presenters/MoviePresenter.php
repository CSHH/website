<?php

namespace App\Presenters;

use App\Model\Entities;

final class MoviePresenter extends SharedContentPresenter
{
    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_MOVIE);
    }
}
