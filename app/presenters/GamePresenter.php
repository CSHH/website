<?php

namespace App\Presenters;

use App\Model\Entities;

final class GamePresenter extends WikiPresenter
{
    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_GAME);
    }
}
