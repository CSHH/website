<?php

namespace App\Admin;

use App\Entities;

final class MovieListingPresenter extends SharedContentListingPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault(10, Entities\WikiEntity::TYPE_MOVIE);
    }
}
