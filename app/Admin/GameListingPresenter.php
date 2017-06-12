<?php

namespace App\Admin;

use App\Entities;

final class GameListingPresenter extends SharedContentListingPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault(10, Entities\WikiEntity::TYPE_GAME);
    }
}
