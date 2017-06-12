<?php

namespace App\Admin;

use App\Entities;

final class BookListingPresenter extends SharedContentListingPresenter
{
    public function actionDefault()
    {
        $this->runActionDefault(10, Entities\WikiEntity::TYPE_BOOK);
    }
}
